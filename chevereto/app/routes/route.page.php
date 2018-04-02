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
	$handler->template = 404;
	$doing = $handler->request[0];
	
	// Get the available pages
	$pages = array_values(array_diff(scandir(G_APP_PATH_THEME . 'pages/'), ['.', '..']));
	
	// Process the second level request like "pages/tos"
	if(!$doing or !in_array($doing.'.php', $pages) or $handler->isRequestLevel(3)) {
		return $handler->issue404();
	}
	
	$handler->template = 'pages/' . $doing;
};