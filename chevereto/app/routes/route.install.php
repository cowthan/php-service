<?php
$route = function($handler) {
	try {
		// Detect if this thin is installed
		if(CHV\Settings::get('chevereto_version_installed')) {
			if(!CHV\Login::getUser()['is_admin']) { // Must be an admin
				G\redirect();
			}
		}
		$install_script = CHV_APP_PATH_INSTALL . 'installer.php';
		if(!file_exists($install_script)) {
			throw new Exception('Missing app/install/installer.php', 100);
		}
		if(!@require_once($install_script)) {
			throw new Exception("Can't include app/install/installer.php", 101);
		}
	} catch(Exception $e) {
		G\exception_to_error($e);
	}
};