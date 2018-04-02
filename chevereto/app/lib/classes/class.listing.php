<?php

/* --------------------------------------------------------------------

  Chevereto
  http://chevereto.com/

  @author	Rodolfo Berrios A. <http://rodolfoberrios.com/>
			<inbox@rodolfoberrios.com>

  Copyright (C) Rodolfo Berrios A. All rights reserved.
  
  BY USING THIS SOFTWARE YOU DECLARE TO ACCEPT THE CHEVERETO EULA
  http://chevereto.com/license

  --------------------------------------------------------------------- */

namespace CHV;
use G, Exception;

class Listing {
	
	private static $valid_types = ['images', 'albums', 'users'];
	private static $valid_sort_types = ['date', 'size', 'views', 'likes', 'id', 'image_count'];
	
	// Set the type of list
	public function setListing($listing) {
		$this->listing = $listing;
	}
	
	// Sets the type of resource being listed
	public function setType($type) {
		$this->type = $type;
	}
	
	// Sets the offset (sql> LIMIT offset,limit)
	public function setOffset($offset) {
		$this->offset = intval($offset);
	}
	
	// Sets the limit (sql> LIMIT offset,limit)
	public function setLimit($limit) {
		$this->limit = intval($limit);
	}
	
	public function setItemsPerPage($count) {
		$this->items_per_page = intval($count);
	}
	
	// Sets the sort type (sql> SORT BY sort_type)
	public function setSortType($sort_type) {
		$this->sort_type = $sort_type == 'date' ? 'id' : $sort_type;
	}
	
	// Sets the sort order (sql> DESC | ASC)
	public function setSortOrder($sort_order) {
		$this->sort_order = $sort_order;
	}
	
	// Sets the WHERE clause
	public function setWhere($where) {
		$this->where = $where;
	}
	
	// Sets the owner id of the content, usefull to add privacy
	public function setOwner($user_id) {
		$this->owner = $user_id;
	}
	
	// Sets the user id of the request, usefull to add privacy
	public function setRequester($user) {
		$this->requester = $user;
	}
	
	// Sets the category
	public function setCategory($category) {
		$this->category = (int)$category;
	}
	
	// Sets the privacy layer of this listing
	public function setPrivacy($privacy) {
		$this->privacy = $privacy;
	}
	
	public function bind($param, $value, $type = null) {
		$this->binds[] = array(
			'param' => $param,
			'value' => $value,
			'type'  => $type
		);
	}
	
	public function getTotals($bool) {
		$this->get_totals = $bool ? true : false;
	}
	
	/**
	 * Do the thing
	 * @Exeption 4xx
	 */
	public function exec($get_total_count=false) {

		$this->validateInput();

		$tables = DB::getTables();
		
		$joins = [
			// Get image + storage + parent album + user uploader
			'images' => [
				'storage'	=> 'LEFT JOIN '.$tables['storages'].' ON '.$tables['images'].'.image_storage_id = '.$tables['storages'].'.storage_id',
				'user'		=> 'LEFT JOIN '.$tables['users'].' ON '.$tables['images'].'.image_user_id = '.$tables['users'].'.user_id',
				'albums'	=> 'LEFT JOIN '.$tables['albums'].' ON '.$tables['images'].'.image_album_id = '.$tables['albums'].'.album_id',
				'categories'=> 'LEFT JOIN '.$tables['categories'].' ON '.$tables['images'].'.image_category_id = '.$tables['categories'].'.category_id',
			],
			'users' => [],
			'albums' => [
				'user'		=> 'LEFT JOIN '.$tables['users'].' ON '.$tables['albums'].'.album_user_id = '.$tables['users'].'.user_id'
			]
		];

		$base_table = $tables[$this->type];
		
		$query = 'SELECT * FROM '.$base_table."\n";
		
		if(G\check_value($joins[$this->type])) {
			$query .=  implode("\n", $joins[$this->type]) . "\n";
		}
		
		if($this->type == 'images' and $this->category) {
			$category_qry = $tables['images'] . '.image_category_id = ' . $this->category;
			if($this->where) {
				$this->where .= ' AND ' . $category_qry;
			} else {
				$this->where = 'WHERE ' . $category_qry;
			}
		}
		
		if($this->where) {
			$query .= $this->where . "\n";
		}
		
		if(empty($this->requester)) {
			$this->requester = Login::getUser();
		} else if(!is_array($this->requester)) {
			$this->requester = User::getSingle($this->requester, 'id');
		}	
		
		// Privacy layer
		if(!$this->requester['is_admin'] and in_array($this->type, array('images', 'albums')) and ((!$this->owner or !$this->requester) or $this->owner !== $this->requester['id'])) {
			
			$query .= ($this->where ? ' AND ' : 'WHERE ');
			
			$nsfw_off = $this->requester ? !$this->requester['show_nsfw_listings'] : !getSetting('show_nsfw_in_listings');
			if($this->type == 'images' and $nsfw_off) {
				$query .= $tables['images'].'.image_nsfw = 0 AND ';
			}
			
			if(getSetting('website_privacy_mode') == 'public' or $this->privacy == 'private_but_link' or getSetting('website_content_privacy_mode') == 'default') {
				$query .= '(' . $tables['albums'].".album_privacy NOT IN ";
				$query .= $this->privacy == 'private_but_link' ? "('private','custom') " : "('private','private_but_link','custom') ";
				$query .=  "OR ".$tables['albums'].'.album_privacy IS NULL OR '.$tables['albums'].'.album_user_id';
				$query .= (!$this->requester ? ' IS NULL' : '='.$this->requester['id']) . ')';
			} else {
				$injected_requester = !$this->requester['id'] ? 0 : $this->requester['id'];
				$query .= '(' . $tables['albums'].'.album_user_id = '.$injected_requester;				
				$query .= $this->type == 'albums' ? ')' : (' OR ' . $tables['images'].'.image_user_id = '.$injected_requester . ')');
			}

		}
		
		$order_by = substr($this->type, 0, -1).'_' . $this->sort_type;
		
		$query .= ' ORDER BY ' . $order_by . ' ' . strtoupper($this->sort_order) . "\n";
		
		if($this->offset < 0 or $this->limit < 0) {
			throw new ListingException('Limit integrity violation', 400);
		}

		$query .= 'LIMIT '.($this->offset).','.($this->limit);
		
		try {
			$db = DB::getInstance();
			$db->query($query);
			
			if(is_array($this->binds)) {
				foreach($this->binds as $bind) {
					$db->bind($bind['param'], $bind['value'], $bind['type']);
				}
			}

			$this->output = $db->fetchAll();
			$this->output = G\safe_html($this->output);
			
			$this->output_assoc = [];
			$formatfn = 'CHV\\' . ucfirst(substr($this->type, 0, -1));
			foreach($this->output as $k => $v) {
				$this->output_assoc[] = $formatfn::formatArray($v);
			}
			
			if(!isset($this->get_totals)) {
				$this->get_totals = true;
			}
			
			if($this->get_totals) {
				$db = DB::getInstance();
				$query = str_replace('SELECT *', 'SELECT COUNT(*) as total', $query);
				$query = str_replace('LIMIT '.($this->offset).','.($this->limit), '', $query);
				$db->query($query);
				
				if(is_array($this->binds)) {
					foreach($this->binds as $bind) {
						$db->bind($bind['param'], $bind['value'], $bind['type']);
					}
				}
				$total_count = $db->fetchSingle()['total'];
				
				if(!$this->items_per_page) {
					$this->items_per_page = $this->limit;
				}

				$this->totals = [
					'count' => (int)$total_count,
					'pages' => (int)ceil($total_count/$this->items_per_page)
				];
			}
			
		} catch(Exception $e) {
			throw new ListingException($e->getMessage(), 400);
		}
		
		// Get album slices and stuff
		if($this->type == 'albums') {
			foreach($this->output as $k => &$album) {
				// Album count
				if($album['album_image_count'] < 0) {
					$album['album_image_count'] = 0;
				}
				$album['album_image_count_label'] = _n('image', 'images', $album['album_image_count']);
				// Album slice
				try {
					$db->query('SELECT * from '.$tables['images'].' LEFT JOIN '.$tables['storages'].' ON '.$tables['images'].'.image_storage_id = '.$tables['storages'].'.storage_id WHERE image_album_id=:album_id ORDER BY image_date ASC LIMIT 0,5');
					$db->bind(':album_id', $album['album_id']);
					$this->output[$k]['album_images_slice'] = $db->fetchAll();
				} catch(Exception $e) {
					throw new ListingException($e->getMessage(), 400);
				}
			}
		
		}
		
		// Get user counts
		/*if($this->type == 'users') {
			if($this->sort_type == 'image_count') {
				G\key_asort($this->output, 'user_image_count');
				$this->output = $this->sort_order == 'desc' ? array_reverse($this->output) : $this->output;
			}
		}*/
		
	}
	
	/**
	 * validate_input aka "first stage validation"
	 * This checks for valid input source data
	 * @Exception 1XX
	 */
	private function validateInput() {
		
		if($this->limit == 1) {
			$this->sort_type = 'date';
			$this->sort_order = 'desc'; 
		}
		
		if(empty($this->offset)) {
			$this->offset = 0;
		}
		
		// Missing values
		$check_missing = ['type', 'offset', 'limit', 'sort_type', 'sort_order'];
		missing_values_to_exception($this, 'CHV\ListingException', $check_missing, 100);
		
		// Validate type
		if(!in_array($this->type, self::$valid_types)) {
			throw new ListingException('Invalid $type "'.$this->type.'"', 110);
		}
		
		// Validate limits
		if($this->offset == 0 && $this->limit == 0) {
			throw new ListingException('$offset and $limit are equal to 0 (zero)', 120);
		}
					
		// Validate sort type
		if(!in_array($this->sort_type, self::$valid_sort_types)) {
			throw new ListingException('Invalid $sort_type "'.$this->sort_type.'"', 130);
		}
		
		// Validate sort order
		if(!preg_match('/^(asc|desc)$/', $this->sort_order)) {
			throw new ListingException('Invalid $sort_order "'.$this->sort_order.'"', 140);
		}
		
	}
	
	public function htmlOutput($tpl_list='images') {
		
		if(is_null($tpl_list)) {
			$tpl_list = 'images';
		}
		
		$directory = new \RecursiveDirectoryIterator(G_APP_PATH_THEME . 'tpl_list_item/');
		$iterator = new \RecursiveIteratorIterator($directory);
		$regex  = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
		
		$filelist = array();
		foreach($regex as $file) {
			$filelist = array_merge($filelist, $file);
		}
		
		$list_item_template = array();
		foreach($filelist as $file) {
			$file = G\forward_slash($file);
			$key = preg_replace('/\\.[^.\\s]{3,4}$/', '', str_replace(G_APP_PATH_THEME, "", $file));
			ob_start();
			require($file);
			$file_get_contents = ob_get_contents();
			ob_end_clean();
			$list_item_template[$key] = $file_get_contents;
		}
		
		$html_output = '';
		$tpl_list = preg_replace('/s$/', '', $tpl_list);

		if(!is_array($this->output)) {
			return;
		}
		
		foreach($this->output as $row) {
			switch($tpl_list) {
				case 'image':
				case 'user/image':
				case 'album/image':
				default:
					$format_fn = 'CHV\Image';
				break;
				case 'album':
				case 'user/album':
					$format_fn = 'CHV\Album';
				break;
				case 'user':
					$format_fn = 'CHV\User';
				break;
			}
			
			$item = $format_fn::formatArray($row);

			if(function_exists('get_peafowl_item_list')) {
				$render = 'get_peafowl_item_list';
			} else {
				$render = 'CHV\Render\get_peafowl_item_list';
			}
			
			$html_output .= $render($tpl_list, $item, $list_item_template, Login::getUser()['id']);
		}
		
		
		
		return $html_output;
	}
	
	public static function getAlbumHtml($album_id, $template='user/albums') {
		try {
			$album = new Listing;
			$album->setType('albums');
			$album->setOffset(0);
			$album->setLimit(1);
			$album->setWhere('WHERE album_id=:album_id');
			$album->bind(':album_id', $album_id);
			$album->exec();
			return $album->htmlOutput($template);
		} catch(Exception $e) {
			throw new ListingException($e->getMessage(), 400);
		}
	}
	
	public static function getParams($json_call=false) {
		
		$items_per_page = getSetting('listing_items_per_page');
		$listing_pagination_mode = getSetting('listing_pagination_mode');
		
		$params = [];
		$params['items_per_page'] = $items_per_page;
		
		if(!$json_call and $listing_pagination_mode == 'endless') {
			$params['page'] = max(intval($_REQUEST['page']), 1);
			$params['limit'] = $params['items_per_page'] * $params['page'];
			$params['offset'] = 0;	
			
			// Switch endless to classic if we are dealing with large listings (from GET)
			if($params['limit'] > getSetting('listing_safe_count')) {
				$listing_pagination_mode = 'classic';
				Settings::setValue('listing_pagination_mode', $listing_pagination_mode );
			}
		}
		
		if(isset($_REQUEST['pagination']) or $listing_pagination_mode == 'classic') { // Static single page display
			$params['page'] = $_REQUEST['page'] ? intval($_REQUEST['page']) - 1 : 0;
			$params['limit'] = $params['items_per_page'];
			$params['offset'] = $params['page']*$params['limit'];
		} else { // Endless scrolling
		}
		
		if($json_call) {
			$params = array_merge($params, [
				'page'	=> $_REQUEST['page'] ? $_REQUEST['page'] - 1 : 0,
				'limit'	=> $items_per_page
			]);
			$params['offset'] = $params['page'] * $params['limit'] + ($_REQUEST['offset'] ? $_REQUEST['offset'] : 0);
		}
		
		$default_sort = [
			0 => 'date',
			1 => 'desc'
		];
		
		preg_match('/(.*)_(asc|desc)/', $_REQUEST['sort'], $sort_matches);
		$params['sort'] = array_slice($sort_matches, 1);
		
		// Empty sort
		if(count($params['sort']) !== 2) {
			$params['sort'] = $default_sort;
		}
		
		// Check sort type
		if(!in_array($params['sort'][0], self::$valid_sort_types)) {
			$params['sort'][0] = $default_sort[0];
		}
		// Check sort order
		if(!in_array($params['sort'][1], ['asc', 'desc'])) {
			$params['sort'][1] = $default_sort[1];
		}
		
		return $params;
		
	}
	
}

class ListingException extends Exception {}