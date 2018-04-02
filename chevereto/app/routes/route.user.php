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
		// 0 -> username
		// 1 -> ?QS | albums | search
		// 2 -> albums/?QS | search/?QS
		
		if($handler->isRequestLevel($handler::getCond('mapped_route') ? 4 : 5)) return $handler->issue404(); // Allow only N levels
		
		// Handle the request for /user and /username routing
		$request_handle = $handler::getCond('mapped_route') ? $handler->request_array : $handler->request;
		
		// Handle the request for /album or /search (personal mode ON + '/' routing)
		if(CHV\getSetting('website_mode') == 'personal' and CHV\getSetting('website_mode_personal_routing') == '/' and in_array($request_handle[0], ['albums', 'search'])) {
			$request_handle = [1 => $request_handle[0]];
		}
		
		// Username handle
		$username = $request_handle[0];	
		
		// Detect mapped args
		if($handler::getCond('mapped_route') and $handler::$mapped_args) {
			$mapped_args = $handler::$mapped_args;	
		}
		
		if($mapped_args['id']) {
			$id = $handler::$mapped_args['id'];
		}
		
		if(is_null($username) and is_null($id)) {
			return $handler->issue404();
		}
		
		// User routing redirect
		if(CHV\getSetting('user_routing') and $handler->request_array[0] == 'user') {
			G\redirect(preg_replace('#/user/#', '/', G\get_current_url(), 1));
		}
		
		$logged_user = CHV\Login::getUser();
		
		// Logged user status override redirect
		CHV\User::statusRedirect($logged_user['status']);
		
		$userhandle = is_null($id) ? 'username' : 'id';
		
		$user = CHV\User::getSingle($$userhandle, $userhandle);
		$albums = CHV\User::getAlbums($user["id"]);

		// No user or invalid status
		if(!$user or ($logged_user and !$logged_user['is_admin'] and $user['status'] !== 'valid')) {
			return $handler->issue404();
		}
		
		// Single user mode redirect
		if(CHV\getSetting('website_mode') == 'personal' and $user['id'] == CHV\getSetting('website_mode_personal_uid') and $handler->request_array[0] == 'user') {
			G\redirect(CHV\getSetting('website_mode_personal_routing'));
		}
		
		$user_cond = array('user_images' => true, 'user_albums' => false, 'user_search' => false);
		
		$is_owner = $user['id'] == CHV\Login::getUser()['id'];
		
		if($request_handle[1]) {
			
			// Sub path user
			if($request_handle[1] !== $_SERVER['QUERY_STRING']) {
				if(!in_array($request_handle[1], array($username, 'albums', 'search'))) {
					return $handler->issue404();
				}
			}
			
			// Search in user
			if($request_handle[1] == 'search') {
				if(!$_SERVER['QUERY_STRING']) {
					return $handler->issue404();
				}
				// Invalid list request?
				if(!empty($_REQUEST['list']) && !in_array($_REQUEST['list'], array('images', 'albums'))) {
					return $handler->issue404();
				}
			}
			
		}
		
		$pre_doctitle = $user['name'];
		if(CHV\getSetting('website_mode') == 'community' or $user['id'] !== CHV\getSetting('website_mode_personal_uid')) {
			$pre_doctitle .= ' ('.$user['username'].')';
		}
		$handler::setVar('pre_doctitle', $pre_doctitle);
		
		if($request_handle[1] == 'albums') {
			$user_cond['user_images'] = false;
			$user_cond['user_albums'] = true;
		}
		
		if($request_handle[1] == 'search') {
			if(!$_REQUEST['q']) {
				G\redirect($user['url']);
			}
			$user_cond['user_images'] = false;
			$user_cond['user_search'] = true;
			$user['search'] = [
				'type'	=> empty($_REQUEST['list']) ? 'images' : $_REQUEST['list'],
				'q'		=> $_REQUEST['q'],
				'd'		=> strlen($_REQUEST['q']) >= 25 ? (substr($_REQUEST['q'], 0, 22) . '...') : $_REQUEST['q']
			];
		}
		
		foreach($user_cond as $k => $v) {
			$handler::setCond($k, $v);
		}
		
		$safe_html_user = G\safe_html($user);
		
		// Tabs
		$base_user_url = $user["url"];

		if($user_cond['user_images']) {
			$tabs = array(
				0 => array(
					"list"		=> true,
					"tools"		=> true,
					"label"		=> _s('Most recent'),
					"id"		=> "list-most-recent",
					"params"	=> "list=images&sort=date_desc&page=1",
					"current"	=> $_REQUEST["sort"] == "date_desc" or !$_REQUEST["sort"] ? true : false,
				),
				1 => array(
					"list"		=> true,
					"tools"		=> true,
					"label"		=> _s('Oldest'),
					"id"		=> "list-most-oldest",
					"params"	=> "list=images&sort=date_asc&page=1",
					"current"	=> $_REQUEST["sort"] == "date_asc",
				),
				2 => array(
					"list"		=> true,
					"tools"		=> true,
					"label"		=> _s('Most viewed'),
					"id"		=> "list-most-viewed",
					"params"	=> "list=images&sort=views_desc&page=1",
					"current"	=> $_REQUEST["sort"] == "views_desc",
				)
			);
			$current = false;
			foreach($tabs as $k => $v) {
				if($v['current']) {
					$current = true;
				}
				$tabs[$k]["type"] = "images";
			}
			if(!$current) {
				$tabs[0]['current'] = true;
			}
		}
		
		if($user_cond['user_albums']) {		
			$base_user_url .= "/albums";
			$tabs = array(
				0 => array(
					"label"			=> _s('Most recent'),
					"id"			=> "list-most-recent",
					"params"		=> "sort=date_desc&page=1",
					"current"		=> $_REQUEST["sort"] == "date_desc" or !$_REQUEST["sort"] ? true : false,
				),
				1 => array(
					"label"			=> _s('Oldest'),
					"id"			=> "list-most-oldest",
					"params"		=> "sort=date_asc&page=1",
					"current"		=> $_REQUEST["sort"] == "date_asc",
				)
			);
			foreach($tabs as $k => $v) {
				$tabs[$k]["type"] = "albums";
			}
		}
		
		if($user_cond['user_search']) {
			$base_user_url .= "/search";
			$tabs = array(
				0 => array(
					"type"		=> "images",
					"label"		=> _s('Images'),
					"id"		=> "list-user-images",
					"current"	=> $_REQUEST["list"] == "images" or !$_REQUEST["list"] ? true : false,
				),
				1 => array(
					"type"		=> "albums",
					"label"		=> _s('Albums'),
					"id"		=> "list-user-albums",
					"current"	=> $_REQUEST["list"] == "albums",
				)
			);
			foreach($tabs as $k => $v) {
				$tabs[$k]["params"] = "list=".$v["type"]."&q=".$safe_html_user["search"]["q"]."&sort=date_desc&page=1";
			}
		}
		
		foreach($tabs as $k => $v) {
			$tabs[$k]["url"] = rtrim($base_user_url, '/') . "/?" . $tabs[$k]["params"];
			$tabs[$k]["params_hidden"] = "";
			if($user_cond['user_albums']) {
				$tabs[$k]["params_hidden"] .= "list=albums&";
			}
			$tabs[$k]["params_hidden"] .= "userid=" . $user["id_encoded"] . "&from=user";
			$tabs[$k]["disabled"] = $user[$user_cond['user_images'] ? "image_count" : "album_count"] == 0 ? !$v["current"] : false;
		}
		
		// Listing
		
		if($user["image_count"] > 0 or $user["album_count"] > 0) {

			$list_params = CHV\Listing::getParams(); // Use CHV magic params
			
			$output_tpl = "user/";
			
			if($user_cond['user_images'] or $user["search"]["type"] == "images") {
				$output_tpl .= "images";
			}
			if($user_cond['user_albums'] or $user["search"]["type"] == "albums") {
				$output_tpl .= "albums";
			}
			
			$type = $user_cond['user_images'] ? "images" : "albums";
			$where = $user_cond['user_images'] ? "WHERE image_user_id=:user_id" : "WHERE album_user_id=:user_id";
			
			if($user_cond['user_search']) {
				$type = $user["search"]["type"];
				$where = $user["search"]["type"] == "images" ? "WHERE image_user_id=:user_id AND MATCH(image_name, image_title, image_description, image_original_filename) AGAINST(:q)" : "WHERE album_user_id=:user_id AND MATCH(album_name, album_description) AGAINST(:q)";
			}
			
			$list = new CHV\Listing;
			$list->setType($type); // images | users | albums
			$list->setOffset($list_params['offset']);
			$list->setLimit($list_params['limit']); // how many results?
			$list->setItemsPerPage($list_params['items_per_page']); // must
			$list->setSortType($list_params['sort'][0]); // date | size | views
			$list->setSortOrder($list_params['sort'][1]); // asc | desc
			$list->setWhere($where);
			$list->setOwner($user["id"]);
			$list->setRequester(CHV\Login::getUser());
			$list->bind(":user_id", $user["id"]);
			if($user_cond['user_search'] and !empty($user['search']['q'])) {
				$list->bind(':q', $q ?: $user['search']['q']);
			}
			$list->output_tpl = $output_tpl;
			$list->exec();
		}
		
		$handler::setCond('owner', $is_owner);
		$handler::setVar('user', $user);
		$handler::setVar('safe_html_user', $safe_html_user);
		$handler::setVar('tabs', $tabs);
		$handler::setVar('list', $list);
		
		// Note, _s must be call like this to bind the PO crawler
		if($user_cond['user_albums']) {
			$meta_description = _s('%n (%u) albums on %w');
		} else {
			if($user['bio']) {
				$meta_description = $safe_html_user['bio'];
			} else {
				$meta_description = _s('%n (%u) on %w');
			}
			
		}
		$handler::setVar('meta_description', strtr($meta_description, ['%n' => $user['name'], '%u' => $user['username'], '%w' => CHV\getSetting('website_name')]));
		
		if($handler::getCond('admin') or $is_owner) {
			$handler::setVar('user_items_editor', [
				"user_albums"	=> CHV\User::getAlbums($user["id"]),
				"type"			=> $user_cond['user_albums'] ? "albums": "images"
			]);
		}
		
	} catch(Exception $e) {
		G\exception_to_error($e);
	}
};