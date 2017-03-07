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
		
		$logged_user = CHV\Login::getUser();
		
		if(!CHV\getSetting('website_explore_page') && !$logged_user['is_admin']) {
			return $handler->issue404();
		}
		
		if($handler->isRequestLevel(2)) return $handler->issue404(); // Allow only 3 levels
		
		// Tabs
		$tabs = array(
			0 => array(
				'list'		=> true,
				'tools'		=> true,
				'label'		=> _s('Most recent'),
				'id'		=> 'list-most-recent',
				'params'	=> 'list=images&sort=date_desc&page=1',
				'current'	=> $_REQUEST['sort'] == 'date_desc' or !$_REQUEST['sort'] ? true : false,
			),
			1 => array(
				'list'		=> true,
				'tools'		=> true,
				'label'		=> _s('Oldest'),
				'id'		=> 'list-most-oldest',
				'params'	=> 'list=images&sort=date_asc&page=1',
				'current'	=> $_REQUEST['sort'] == 'date_asc',
			),
			2 => array(
				'list'		=> true,
				'tools'		=> true,
				'label'		=> _s('Most viewed'),
				'id'		=> 'list-most-viewed',
				'params'	=> 'list=images&sort=views_desc&page=1',
				'current'	=> $_REQUEST['sort'] == 'views_desc',
			)
		);
		$current = false;
		foreach($tabs as $k => $v) {
			if($v['current']) {
				$current = true;
			}
			$tabs[$k]['type'] = 'images';
			$route_path = G\get_route_name();
			$tabs[$k]['url'] = G\get_base_url(rtrim($route_path . '/', '/') . '?' . $tabs[$k]['params']);
		}
		if(!$current) {
			$tabs[0]['current'] = true;
		}
		
		// List
		$list_params = CHV\Listing::getParams(); // Use CHV magic params
		$list = new CHV\Listing;
		$list->setType('images');
		$list->setOffset($list_params['offset']);
		$list->setLimit($list_params['limit']); // how many results?
		$list->setItemsPerPage($list_params['items_per_page']); // must
		$list->setSortType($list_params['sort'][0]); // date | size | views
		$list->setSortOrder($list_params['sort'][1]); // asc | desc
		$list->setRequester(CHV\Login::getUser());
		$list->exec();
		
		$handler::setVar('pre_doctitle', _s('Explore'));
		$handler::setVar('meta_keywords', NULL);
		$handler::setVar('category', NULL);
		$handler::setVar('tabs', $tabs);
		$handler::setVar('list', $list);
		
		if($logged_user['is_admin']) {
			$handler::setVar('user_items_editor', false);
		}
		
	} catch(Exception $e) {
		G\exception_to_error($e);
	}
};