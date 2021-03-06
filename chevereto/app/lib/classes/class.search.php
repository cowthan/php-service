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

class Search {
	
	public static $excluded = ['storage', 'ip'];
	
	public function __construct() {
		$this->DBEngine = DB::queryFetchSingle("SHOW TABLE STATUS WHERE Name = '".DB::getTable('images')."';")['Engine'];
	}
	
	public function build() {
		
		// Validate type
		if(!in_array($this->type, ['images', 'albums', 'users'])) {
			throw new SearchException("Invalid search type in ".__METHOD__, 100);
		}

		// Advanced search handle
		$as_handle = ['as_q' => NULL, 'as_epq' => NULL, 'as_oq' => NULL, 'as_eq' => NULL, 'as_cat' => 'category'];
		$as_handle_admin = ['as_stor' => 'storage', 'as_ip' => 'ip'];
		if($this->requester['is_admin']) {
			$as_handle = array_merge($as_handle, $as_handle_admin);
		}
		foreach($as_handle as $k => $v) {
			if(G\check_value($this->request[$k])) {
				$this->q .= ' ' . (!is_null($v) ? $v.':' : '') . $this->request[$k];
			}
		}
		
		// Clean q
		$this->q = trim(preg_replace(['#\"+#', '#\'+#'], ['"', '\''], $this->q));
		
		$search_op = $this->handleSearchOperators($this->q, $this->requester['is_admin'] == true);
		
		// Make q pretty
		$this->q = NULL;
		foreach($search_op as $operator) {
			$this->q .= implode(' ', $operator) . ' ';
		}
		$this->q = preg_replace('/\s+/', ' ', trim($this->q));
		
		// Get the full text match query
		$q_match = $this->q; // Note... Why MyISAM has issues with exclusions like -700 ?
		$seach_binds = [];
		
		foreach($search_op['named'] as $v) {
			
			if($this->type !== 'images') continue;
			
			$q_match = trim(preg_replace('/\s+/', ' ', str_replace($v, '', $q_match)));
			
			$op = explode(':', $v);
			if(!in_array($op[0], ['category', 'ip', 'storage'])) {
				continue;
			}
			switch($op[0]) {
				case 'category':
					$search_op_wheres[] = 'category_url_key = :category';
					$seach_binds[] = ['param' => ':category', 'value' => $op[1]];
				break;
				
				case 'ip':
					// Validate input IP
					/*if(!G\is_valid_ip($op[1])) {
						continue;
					}*/
					$search_op_wheres[] = 'image_uploader_ip = :ip';
					$seach_binds[] = ['param' => ':ip', 'value' => G\str_replace_first('ip:', NULL, $this->q)];
				break;
				
				case 'storage':
					if(!filter_var($op[1], FILTER_VALIDATE_INT) and !in_array($op[1], ['local', 'external'])) {
						break;
					}
					$storage_operator_clause = [
						$op[1]		=> '= :storage_id',
						'local'		=> 'IS NULL',
						'external'	=> 'IS NOT NULL'
					];
					
					if(filter_var($op[1], FILTER_VALIDATE_INT)) {
						$seach_binds[] = ['param' => ':storage_id', 'value' => $op[1]];
					}
					
					$search_op_wheres[] = 'image_storage_id ' . ($storage_operator_clause[$op[1]]);
				break;

			}
		}
		
		if($q_match) {
			$q_value = $q_match;
			if($this->DBEngine == 'InnoDB') {
				$q_value = trim($q_value, '><');
			}
			$seach_binds[] = ['param' => ':q', 'value' => $q_value];
		}
		
		$this->binds = $seach_binds;
		$this->op = $search_op;
		$where = NULL;
		
		$q_replacement = $this->DBEngine == 'InnoDB' ? "':q'" : ':q';
		
		switch($this->type) {
			case 'images':
				if($q_match) {
					$where = "WHERE MATCH(image_name, image_title, image_description, image_original_filename) AGAINST(:q IN BOOLEAN MODE)";
				}
				if(count($search_op_wheres) > 0) {
					$where .= (is_null($where) ? 'WHERE ' : ' AND ') . implode(' AND ', $search_op_wheres);
				}
			break;
			case 'albums':
				$where = "WHERE MATCH(album_name, album_description) AGAINST(:q)";
			break;
			case 'users':
				$where = "WHERE MATCH(user_name, user_username) AGAINST(:q)";
			break;
		}
		
		$this->wheres = $where;

		$this->display = [
			'type'	=> $this->type,
			'q'		=> $this->q,
			'd'		=> strlen($this->q) >= 25 ? (substr($this->q, 0, 22) . '...') : $this->q,
		];
	}
	
	private function handleSearchOperators($q, $full=true) {
		
		$return = [];
		$operators = ['any' => [], 'exact_phrases' => [], 'excluded' => [], 'named' => []];
		
		$raw_ops = [];
		$raw_regex = [
			'named' => '[\S]+\:[\S]+', // take all the like:this operators
			'quoted'=> '-*[\"\']+.+[\"\']+', // take all the "quoted stuff" "like" "this, one"
			'spaced'=> '\S+' // Take all the space separated stuff
		];
		
		foreach($raw_regex as $k => $v) {
			if($k == 'spaced') {
				$q = str_replace(',', '', $q); // Don't need commas
			}
			if(preg_match_all('/' . $v . '/', $q, $match)) {
				$raw_ops[$k] = $match[0];
				foreach($match[0] as $v) {
					switch($k) {
						case 'named':
							if(!$full) {
								$named_operator = explode(':', $v);
								if(in_array($named_operator[0], self::$excluded)) {
									continue;
								}
							}
							$operators[$k][] = $v;
						break;
						default:
							if(0 === strpos($v, '-')) {
							   $operators['excluded'][] = $v;
							} else if(0 === strpos($v, '"')) {
								$operators['exact_phrases'][] = $v;
							} else {
								$operators['any'][] = $v;
							}
						break;
					}
					$q = trim(preg_replace('/\s+/', ' ', str_replace($v, '', $q)));
				}
			}
			
		}
		
		return $operators;

	}
}

class SearchException extends Exception {}