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
		
		if($handler->isRequestLevel(4)) return $handler->issue404(); // Allow only 3 levels
		
		if(is_null($handler->request[0])) {
			return $handler->issue404();
		}
		
		$logged_user = CHV\Login::getUser();
		
		// User status override redirect
		CHV\User::statusRedirect($logged_user['status']);

		$id = CHV\decodeID($handler->request[0]);
		$tables = CHV\DB::getTables();
		
		$album = CHV\Album::getSingle($id);
		
		// No album or belogns to a banned user?
		if(!$album  or (!$logged_user['is_admin'] and $album['user']['status'] !== 'valid')) {
			return $handler->issue404();
		}

		$is_owner = $album['user']['id'] == $logged_user['id'];
		
		// Privacy
		if($handler::getCond('forced_private_mode')) {
			$album['privacy'] = CHV\getSetting('website_content_privacy_mode');
		}
		if(!$handler::getCond('admin') and in_array($album['privacy'], array('private', 'custom')) and !$is_owner) {
			return $handler->issue404();
		}
		
		$safe_html_album = G\safe_html($album);
		
		// List
		$list_params = CHV\Listing::getParams(); // Use CHV magic params
		
		$type = 'images';
		$where = 'WHERE image_album_id=:image_album_id';
		
		$list = new CHV\Listing;
		$list->setType($type); // images | users | albums
		$list->setOffset($list_params['offset']);
		$list->setLimit($list_params['limit']); // how many results?
		$list->setItemsPerPage($list_params['items_per_page']); // must
		$list->setSortType($list_params['sort'][0]); // date | size | views
		$list->setSortOrder($list_params['sort'][1]); // asc | desc
		$list->setOwner($album["user"]["id"]);
		$list->setRequester(CHV\Login::getUser());
		$list->setWhere($where);
		$list->setPrivacy($album["privacy"]);
		$list->bind(":image_album_id", $album["id"]);
		$list->output_tpl = 'album/image';
		$list->exec();
		
		// Tabs
		$tabs = array(
			array(
				"list"		=> true,
				"tools"		=> true,
				"label"		=> _s('Most recent'),
				"id"		=> "list-most-recent",
				"params"	=> "sort=date_desc&page=1",
				"current"	=> $_REQUEST["sort"] == "date_desc" or !$_REQUEST["sort"] ? true : false,
			),
			array(
				"list"		=> true,
				"tools"		=> true,
				"label"		=> _s('Oldest'),
				"id"		=> "list-most-oldest",
				"params"	=> "sort=date_asc&page=1",
				"current"	=> $_REQUEST["sort"] == "date_asc",
			),
			array(
				"list"		=> true,
				"tools"		=> true,
				"label"		=> _s('Most viewed'),
				"id"		=> "list-most-viewed",
				"params"	=> "sort=views_desc&page=1",
				"current"	=> $_REQUEST["sort"] == "views_desc",
			)
		);

		if(CHV\getSetting('theme_show_social_share')) {
			$tabs[] = array(
				"list"		=> false,
				"tools"		=> false,
				"label"		=> _s('Share'),
				"id"		=> "tab-share",
			);
		}
		
		if($logged_user['is_admin']) {
			$tabs[] = [
				"list"		=> false,
				"tools"		=> false,
				"label"		=> _s('Full info'),
				"id"		=> "tab-full-info",
			];
		}
		
		$current = false;
		foreach($tabs as $k => $v) {
			if($v["params"]) {
				if($v['current']) {
					$current = true;
				}
				$tabs[$k]['type'] = 'images';
				$tabs[$k]["url"] = $album["url"] . "/?" . $tabs[$k]["params"];
				$tabs[$k]["params_hidden"] = "list=images&from=album&albumid=".$album["id_encoded"];
				$tabs[$k]["disabled"] = $album["image_count"] == 0 ? !$v["current"] : false;
			}
		}
		if(!$current) {
			$tabs[0]['current'] = true;
		}
		
		$handler::setCond('owner', $is_owner);
		$handler::setVars([
			'pre_doctitle'		=> $safe_html_album['name'],
			'album'				=> $album,
			'album_safe_html'	=> $safe_html_album,
			'tabs'				=> $tabs,
			'list'				=> $list,
			'owner'				=> $album['user']
		]);
		
		// Populate the album meta description
		if($album['description']) {
			$meta_description = $album['description'];
		} else {
			$meta_description = _s('%a album hosted in %w', ['%a' => $album['name'], '%w' => CHV\getSetting('website_name')]);
		}
		$handler::setVar('meta_description', htmlspecialchars($meta_description));
		
		// Items editor
		if($handler::getCond('admin') or $is_owner) {
			$handler::setVar('user_items_editor', [
				"user_albums"	=> CHV\User::getAlbums($album["user"]["id"]),
				"type"			=> "images"
			]);
		}
		
		// Sharing
		$share_element = array(
			"referer"		=> G\get_base_url(),
			"url"			=> $album["url"],
			"title"			=> $safe_html_album["name"]
		);
		$share_element["HTML"] = '<a href="'.$share_element["url"].'" title="'.$share_element["title"].'">'.$safe_html_album["name"].' ('.$album['image_count'].' '._n('image', 'images', $album['user']['image_count']).')</a>';
		$share_links_array = CHV\render\get_share_links($share_element);
		
		$handler::setVar('share_links_array', $share_links_array);
		
		// Share modal
		$handler::setVar('share_modal', [
			"type"			=> "album",
			"url"			=> $album["url"],
			"links_array"	=> $share_links_array,
			"privacy"		=> $album["privacy"]
		]);
	
	} catch(Exception $e) {
		G\exception_to_error($e);
	}
};