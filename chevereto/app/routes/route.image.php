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

$route = function($handler) {
	try {
		
		if($handler->isRequestLevel(3)) return $handler->issue404(); // Allow only 2 levels
		
		if(is_null($handler->request[0])) {
			return $handler->issue404();
		}
		
		$logged_user = CHV\Login::getUser();
		
		// User status override redirect
		CHV\User::statusRedirect($logged_user['status']);

		$id = CHV\decodeID($handler->request[0]);
		
		$tables = CHV\DB::getTables();
		
		if($id==0) {
			return $handler->issue404();
		}
		
		// Trail this view
		$_SESSION['last_viewed_image'] = CHV\encodeId($id);
		
		// Session stock viewed images
		if(!$_SESSION['image_view_stock']) {
			$_SESSION['image_view_stock'] = [];
		}
		
		// Get image DB
		$image = CHV\Image::getSingle($id, !in_array($id, $_SESSION['image_view_stock']) ? true : false, true);
		
		// Stock this image view
		$_SESSION['image_view_stock'][] = $id;
		
		// No image or belongs to a banned user if exists?
		if(!$image or (!$logged_user['is_admin'] and !is_null($image['user']['status']) and $image['user']['status'] !== 'valid')) {
			return $handler->issue404();
		}
		
		if($image['file_resource']['type'] == 'url') {
			$url = preg_replace('/^https:/i', 'http:', $image['file_resource']['chain']['image']);
			$headers = G\getUrlHeaders($url, [CURLOPT_REFERER => G\get_current_url()]); // Add referrer for some CDN restrictions
			if($headers['http_code'] !== 200) {
				error_log("Can't fetch file header from external storage server: $url");
				return $handler->issue404();
			}
		} else {
			if(!$image['file_resource']['chain']['image'] or !file_exists($image['file_resource']['chain']['image'])) {
				//CHV\Image::delete($id);
				return $handler->issue404();
			}
		}

		$is_owner = $image['user']['id'] !== NULL ? ($image['user']['id'] == $logged_user['id']) : false;
		
		// Privacy
		if($handler::getCond('forced_private_mode')) {
			$image['album']['privacy'] = CHV\getSetting('website_content_privacy_mode');
		}
		if(!$handler::getCond('admin') and in_array($image['album']['privacy'], array('private', 'custom')) and !$is_owner) {
			return $handler->issue404();
		}
		
		$db = CHV\DB::getInstance();
		
		// User found
		if($image['user']['id'] !== NULL) {
			
			// Get user albums
			$name_array = explode(' ', $image['user']['name']);
			$user_name_short = $name_array[0];
			
			$image['user']['albums'] = [];
			
			// Lets fake the stream as an album
			$image['user']['albums']['stream'] = CHV\User::getStreamAlbum($image['user']);
			
			// Get user album list
			$image['user']['albums'] += CHV\DB::get('albums', ['user_id' => $image['user']['id']], 'AND', ['field' => 'date', 'order' => 'asc']);
			
			foreach($image['user']['albums'] as $k => $v) {
				$image['user']['albums'][$k] = CHV\DB::formatRow($v, 'album');
				CHV\Album::fill($image['user']['albums'][$k]);
			}
			
		}

		// Get the album slice
		if($image['album']['id'] !== NULL) {
			$get_album_slice = CHV\Image::getAlbumSlice($image['id'], $image['album']['id'], 2);
			$image_album_slice_db = $get_album_slice['db'];
			$image_album_slice = array_merge($image['album'], $get_album_slice['formatted']);
		}
		
		$image_safe_html =  G\safe_html($image);
		
		$pre_doctitle = $image_safe_html['title'] ?: ($image_safe_html['name'].'.'.$image_safe_html['extension']) . ' hosted at ' . CHV\getSetting('website_name');
		
		$tabs = [
			[
				"label"		=> _s('About'),
				"id"		=> "tab-about",
				"current"	=> true,
			]
		];
		if(CHV\getSetting('theme_show_embed_content')) {
			$tabs[] = [
				"label"		=> _s('Embed codes'),
				"id"		=> "tab-codes",
			];
		}
		
		if($handler::getCond('admin')) {
			$tabs[] = [
				"label"		=> _s('Full info'),
				"id"		=> "tab-full-info"
			];
			
			// Banned uploader IP?
			$banned_uploader_ip = CHV\Ip_ban::getSingle(['ip' => $image['uploader_ip']]);
			
			// Admin list values
			$image_admin_list_values = [
				[
					'label'		=> _s('Image ID'),
					'content'	=> $image['id'] . ' ('.$image['id_encoded'].')'
				],
				[
					'label'		=> _s('Uploader IP'),
					'content'	=> sprintf(str_replace('%IP','%1$s', '<a href="'.CHV\getSetting('ip_whois_url').'" target="_blank">%IP</a> · <a href="'.G\get_base_url('search/images/?q=ip:%IP').'">'._s('search content').'</a>  ·  ' . (!$banned_uploader_ip ? ('<a data-modal="form" data-args="%IP" data-target="modal-add-ip_ban" data-options=\'{"forced": true}\' data-content="ban_uploader_ip">' . _s('Ban IP') . '</a>') : NULL) . '<span class="'. ($banned_uploader_ip ? NULL : 'soft-hidden') .'" data-content="banned_uploader_ip">'._s('IP already banned').'</span>'), $image['uploader_ip'])
				],
				[
					'label' 	=> _s('Upload date'),
					'content'	=> $image['date']
				],
				[
					'label' 	=> '',
					'content' 	=> $image['date_gmt'] . ' (GMT)'
				]
			];
			
			$handler::setVar('image_admin_list_values', $image_admin_list_values);
			$handler::setCond('banned_uploader_ip', (bool)$banned_uploader_ip);
		}
		
		$handler::setCond('owner', $is_owner);
		$handler::setVar('pre_doctitle', $pre_doctitle);
		$handler::setVar('image_album_slice_db', $image_album_slice_db);
		$handler::setVar('image', $image);
		$handler::setVar('image_safe_html', $image_safe_html);
		$handler::setVar('image_album_slice', G\safe_html($image_album_slice));
		$handler::setVar('tabs', $tabs);
		$handler::setVar('owner', $image['user']);
		
		// Populate image category to meta keywords
		$category = $handler::getVar('categories')[$image['category_id']];
		if($category) {
			$handler::setVar('meta_keywords', _s('%s images', $category['name']) . ', ' . $handler::getVar('meta_keywords'));
		}
		
		// Populate the image meta description
		if($image['description']) {
			$meta_description = $image['description'];
		} else {
			if($image['album']['name']) {
				$meta_description = _s('Image %i in %a album', ['%i' => $image[is_null($image['title']) ? 'filename' : 'title'], '%a' => $image['album']['name']]);
			} else if($image['category']['id']) {
				$meta_description = _s('Image %i in %c category', ['%i' => $image[is_null($image['title']) ? 'filename' : 'title'], '%c' => $image['category']['name']]);
			} else {
				$meta_description = _s('Image %i hosted in %w', ['%i' => $image[is_null($image['title']) ? 'filename' : 'title'], '%w' => CHV\getSetting('website_name')]);
			}
		}
		$handler::setVar('meta_description', htmlspecialchars($meta_description));
		
		if($handler::getCond('admin') or $is_owner) {
			$handler::setVar('user_items_editor', [
				'user_albums'	=> $image['user']['albums'],
				'type'			=> 'image',
				'album'			=> $image['album'],
				'category_id'	=> $image['category_id']
			]);
		}
		
		// Share thing
		$share_element = [
			'referer'		=> G\get_base_url(),
			'url'			=> $image['url_viewer'],
			'image'			=> $image['url'],
			'title'			=> $handler::getVar('pre_doctitle')
		];
		$share_element['HTML'] = '<a href="'.$share_element["url"].'" title="'.$share_element["title"].'"><img src="'.$share_element["image"].'" /></a>';
		$share_links_array = CHV\render\get_share_links($share_element);
		$handler::setVar('share_links_array', $share_links_array);
		
		// Share modal
		$handler::setVar('share_modal', [
			'type'			=> 'image',
			'url'			=> $image['url_viewer'],
			'links_array'	=> $share_links_array,
			'privacy'		=> $image['album']['privacy']
		]);
		
	} catch(Exception $e) {
		G\exception_to_error($e);
	}
};