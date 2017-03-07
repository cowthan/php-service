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
	
		if($_POST and !$handler::checkAuthToken($_REQUEST['auth_token'])) {
			$handler->template = 'request-denied';
			return;
		}
		
		$logged_user = CHV\Login::getUser();
		
		if(!$logged_user) {
			G\redirect(G\get_base_url('login'));
		}
		
		if(!$logged_user['is_admin']) {
			return $handler->issue404();
		}
		
		$route_prefix = 'dashboard';
		$sub_routes = [
			'stats'		=> _s('Stats'),
			'images'	=> _s('Images'),
			'albums'	=> _s('Albums'),
			'users'		=> _s('Users'),
			'settings'	=> _s('Settings')
		];
		
		$default_route = 'stats';
		$doing = $handler->request[0];
		
		// Hack the user settings route
		if($doing == 'user') {
			$route = $handler->getRouteFn('settings');
			$handler::setCond('dashboard_user', true);
			return $route($handler);
		}
		
		if(!is_null($doing) and !array_key_exists($doing, $sub_routes)) {
			return $handler->issue404();
		}
		
		if($doing == '') $doing = $default_route;

		// Populate the routes
		foreach($sub_routes as $route => $label) {
			$aux = str_replace('_', '-', $route);
			$handler::setCond($route_prefix.'_'.$aux, $doing == $aux);			
			if($handler::getCond($route_prefix.'_'.$aux)) {
				$handler::setVar($route_prefix, $aux);
			}
			$route_menu[$route] = array(
				'label' => $label,
				'url'	=> G\get_base_url($route_prefix . ($route == $default_route ? '' : '/'.$route)),
				'current' => $handler::getCond($route_prefix.'_'.$aux)
			);
		}
		
		$handler::setVar($route_prefix.'_menu', $route_menu);
		$handler::setVar('tabs', $route_menu);
		
		// conds
		$is_error = false;
		$is_changed = false;
		
		// vars
		$input_errors = array();
		$error_message = NULL;
		
		if($doing == '') {
			$doing = 'stats';
		}
		
		// Old and new image size counter
		$image_size_count_qry = version_compare(CHV\getSetting('chevereto_version_installed'), '3.5.5', '<') ? 'SELECT SUM(image_size) as count' : 'SELECT (SUM(image_size) + SUM(image_thumb_size) + SUM(image_medium_size)) as count';
		
		switch($doing) {
		
			case 'stats':
				
				$count_tpl = 'SELECT
					COUNT(IF(%date_field% >= DATE_SUB(NOW(), INTERVAL 1 MINUTE), 1, NULL)) AS minute,
					COUNT(IF(%date_field% >= DATE_SUB(NOW(), INTERVAL 1 HOUR), 1, NULL)) AS hour,
					COUNT(IF(%date_field% >= DATE_SUB(NOW(), INTERVAL 1 DAY), 1, NULL)) AS day,
					COUNT(IF(%date_field% >= DATE_SUB(NOW(), INTERVAL 1 DAY), 2, NULL)) AS two_days,
					COUNT(IF(%date_field% >= DATE_SUB(NOW(), INTERVAL 1 WEEK), 1, NULL)) AS week,
					COUNT(IF(%date_field% >= DATE_SUB(NOW(), INTERVAL 1 MONTH), 1, NULL)) AS month,
					COUNT(IF(%date_field% >= DATE_SUB(NOW(), INTERVAL 1 YEAR), 1, NULL)) AS year,
					COUNT(*) as total
				FROM %table%';
				
				
				
				$counts = [
					'image'	=> CHV\DB::queryFetchSingle(strtr($count_tpl, ['%date_field%' => 'image_date', '%table%' => CHV\DB::getTable('images')])),
					'user'	=> CHV\DB::queryFetchSingle(strtr($count_tpl, ['%date_field%' => 'user_date', '%table%' => CHV\DB::getTable('users')])),
					'album'	=> CHV\DB::queryFetchSingle(strtr($count_tpl, ['%date_field%' => 'album_date', '%table%' => CHV\DB::getTable('albums')])),
					'disk'	=> ['used' => CHV\DB::queryFetchSingle(sprintf($image_size_count_qry . ' from %s', CHV\DB::getTable('images')))['count'], 'unit' => 'B']
				];
				
				// Convert null counts to zero
				foreach($counts as $key => &$count) {
					foreach($count as $k => &$v) {
						if(is_null($v)) {
							$v = 0;
						}
					}
				}
				
				$nice_counts = [];
				foreach(['image', 'user', 'album'] as $v) {
					$nice_counts[$v] = [];
					foreach($counts[$v] as $kk => $vv) {
						$nice_counts[$v][$kk] = G\abbreviate_number($vv);
					}
				}
				$format_disk_ussage = explode(' ', G\format_bytes($counts['disk']['used']));				
				$nice_counts['disk'] = ['used' => $format_disk_ussage[0], 'unit' => $format_disk_ussage[1]];
				
				
				if(empty($nice_counts['disk']['used'])) {
					$nice_counts['disk'] = [
						'used' => 0,
						'unit' => 'KB'
					];
				}
				
				$db = CHV\DB::getInstance();
				
				$chv_version = [
					'files'	=> G\get_app_version(),
					'db'	=> CHV\getSetting('chevereto_version_installed')
				];
				
				$system_values = [
					'chv_version' => [
						'label' => _s('Chevereto version'),
						'content' =>  ($chv_version['files'] == $chv_version['db'] ? 
						$chv_version['files'] : 
						$chv_version['files'] . 
						' ('
						.$chv_version['db']
						.' DB) <a href=\"'
						.G\get_base_url('install')
						.'\">'
						._s('update')
						.'</a>')
						. '</a>' 
					],
					'g_version' => [
						'label' => 'G\\',
						'content' => '<a href=\"http://gbackslash.com\" target=\"_blank\">G\\ Library '.G\get_version().'</a>'
					],
					'php_version' => [
						'label' => _s('PHP version'),
						'content' => PHP_VERSION
					],
					'server' => [
						'label' => _s('Server'),
						'content' => gethostname() . ' ' . PHP_OS . '/' . PHP_SAPI 
					],
					'mysql_version' => [
						'label' => _s('MySQL version'),
						'content' => $db->getAttr(PDO::ATTR_SERVER_VERSION)
					],
					'mysql_server_info' => [
						'label' => _s('MySQL server info'),
						'content' => $db->getAttr(PDO::ATTR_SERVER_INFO)
					],
					'gdversion' => [
						'label' => _s('GD Library'),
						'content' => 'Version ' . gd_info()['GD Version'] . ' JPEG:'.gd_info()['JPEG Support'].' GIF:'.gd_info()['GIF Read Support'].'/'.gd_info()['GIF Create Support'].' PNG:'.gd_info()['PNG Support'].' WBMP:'.gd_info()['WBMP Support'].' XBM:'.gd_info()['XBM Support']
					],
					'file_uploads' => [
						'label' => _s('File uploads'),
						'content' => ini_get('file_uploads') == 1 ? _s('Enabled') : _s('Disabled')
					],
					'max_upload_size' => [
						'label' => _s('Max. upload size'),
						'content' => G\format_bytes(G\get_ini_bytes(ini_get('upload_max_filesize')))
					],
					'max_post_size' => [
						'label' => _s('Max. post size'),
						'content' => G\format_bytes(G\get_ini_bytes(ini_get('post_max_size')))
					],
					'max_execution_time' => [
						'label' => _s('Max. execution time'),
						'content' => strtr(_n('%d second', '%d seconds', ini_get('max_execution_time')), ['%d' => ini_get('max_execution_time')])
					],
					'memory_limit' => [
						'label' => _s('Memory limit'),
						'content' => G\format_bytes(G\get_ini_bytes(ini_get('memory_limit')))
					],
				];
				
				$handler::setVar('system_values', $system_values);
				$handler::setVar('counts', $counts);
				$handler::setVar('nice_counts', $nice_counts);
				
			break;
			
			case 'settings':
				
				if($handler->isRequestLevel(4)) {
					return $handler->issue404();
				}
				
				$handler::setCond('show_submit', true);
				
				$settings_sections = [
					'website'				=> _s('Website'),
					'content'				=> _s('Content'),
					'listings'				=> _s('Listings'),
					'image-upload'			=> _s('Image upload'),
					'categories'			=> _s('Categories'),
					'users'					=> _s('Users'),
					'flood-protection'		=> _s('Flood protection'),
					'theme'					=> _s('Theme'),
					'homepage'				=> _s('Homepage'),
					'banners'				=> _s('Banners'),
					'system'				=> _s('System'),
					'languages'				=> _s('Languages'),
					'external-storage'		=> _s('External storage'),
					'email'					=> _s('Email'),
					'social-networks'		=> _s('Social networks'),
					'external-services'		=> _s('External services'),
					'ip-bans'				=> _s('IP bans'),
					'api'					=> 'API',
					'additional-settings'	=> _s('Additional settings'),
				];
				
				foreach($settings_sections as $k => $v) {
					$current = $handler->request[1] ? ($handler->request[1] == $k) : ($k == 'website');
					$settings_sections[$k] = [
						'key'		=> $k,
						'label'		=> $v,
						'url'		=> G\get_base_url($route_prefix.'/settings/'.$k),
						'current'	=> $current
					];
					if($current) {
						$handler::setVar('settings', $settings_sections[$k]);
						if(in_array($k, ['categories', 'ip-bans'])) {
							$handler::setCond('show_submit', false);
						}
					}
					
				}			
				$handler::setVar('settings_menu', $settings_sections);
				//$handler::setVar('tabs', $settings_sections);
				
				switch($handler->request[1]) {
					case 'external-storage':
						$storage_usage = [
							'local'		=> [
								'label' => _s('Local'),
								'bytes'	=> CHV\DB::queryFetchSingle(sprintf($image_size_count_qry . ' from %s WHERE image_storage_id IS NULL', CHV\DB::getTable('images')))['count']
							],
							'external'	=> [
								'label' => _s('External'),
								'bytes' => CHV\DB::queryFetchSingle(sprintf($image_size_count_qry . ' from %s WHERE image_storage_id IS NOT NULL', CHV\DB::getTable('images')))['count']
							]
						];
						$storage_usage['all'] = [
							'label' => _s('All'),
							'bytes' => $storage_usage['local']['bytes'] + $storage_usage['external']['bytes']
						];
						foreach($storage_usage as $k => &$v) {
							if(empty($v['bytes'])) { $v['bytes'] = 0; }
							$v['link'] = '<a href="'.G\get_base_url('search/images/?q=storage:'.$k).'" target="_blank">'._s('search content').'</a>';
							$v['formatted_size'] = G\format_bytes($v['bytes'], 2);
						}
						
						$handler::setVar('storage_usage', $storage_usage);
					break;
				}
				
				if($_POST) {
					
					// Do some cleaning...
					
					// Remove bad formatting and duplicates
					if($_POST['theme_home_uids']) {
						$_POST['theme_home_uids'] = implode(',', array_keys(array_flip(explode(',', trim(preg_replace(['/\s+/', '/,+/'], ['', ','], $_POST['theme_home_uids']), ',')))));
					}
					
					// Personal mode stuff
					if($_POST['website_mode'] == 'personal') {
						$_POST['website_mode_personal_routing'] = G\get_regex_match(CHV\getSetting('routing_regex'), '#', $_POST['website_mode_personal_routing'], 1);
						
						if(!G\check_value($_POST['website_mode_personal_routing'])) {
							$_POST['website_mode_personal_routing'] = '/';
						}
					}
					
					if(isset($_POST['homepage_cta_fn_extra'])) {
						$_POST['homepage_cta_fn_extra'] = trim($_POST['homepage_cta_fn_extra']);
					}
					
					// Columns number
					foreach(['phone', 'phablet', 'laptop', 'desktop'] as $k) {
						if($_POST['listing_columns_' . $k]) {
							$key = 'listing_columns_' . $k;
							$val = $_POST[$key];
							$_POST[$key] = (filter_var($val, FILTER_VALIDATE_INT) and $val > 0) ? $val : CHV\get_chv_default_setting($key);
						}
					}
					
					// HEX color
					if($_POST['theme_main_color']) {
						$_POST['theme_main_color'] = '#' . ltrim($_POST['theme_main_color'], '#');
					}
					
					$editing_array = $_POST;
					
					// Validations
					$validations = [
						'website_name'	=>
							[
								'validate'	=> $_POST['website_name'] ? true : false,
								'error_msg'	=> _s('Invalid website name')
							],
						'default_language'	=>
							[
								'validate'	=> CHV\get_available_languages()[$_POST['default_language']] ? true : false,
								'error_msg'	=> _s('Invalid language')
							],
						'default_timezone'	=>
							[
								'validate'	=> in_array($_POST['default_timezone'], timezone_identifiers_list()),
								'error_msg'	=> _s('Invalid timezone')
							],
						'listing_items_per_page' =>
							[
								'validate'	=> is_numeric($_POST['listing_items_per_page']) and $_POST['listing_items_per_page'] > 0,
								'error_msg'	=> _s('Invalid value')
							],
						'upload_storage_mode'	=>
							[
								'validate'	=> in_array($_POST['upload_storage_mode'], ['datefolder', 'direct']),
								'error_msg'	=> _s('Invalid upload storage mode')
							],
						'upload_filenaming'	=>
							[
								'validate'	=> in_array($_POST['upload_filenaming'], ['original', 'random', 'mixed']),
								'error_msg'	=> _s('Invalid upload filenaming')
							],
						'upload_thumb_width'=>
							[
								'validate'	=> is_numeric($_POST['upload_thumb_width']),
								'error_msg'	=> _s('Invalid thumb width')
							],
						'upload_thumb_height'=>
							[
								'validate'	=> is_numeric($_POST['upload_thumb_height']),
								'error_msg'	=> _s('Invalid thumb height')
							],
						'upload_medium_width'=>
							[
								'validate'	=> is_numeric($_POST['upload_medium_width']),
								'error_msg'	=> _s('Invalid medium width')
							],
						'watermark_percentage' =>
							[
								'validate' 	=> is_numeric($_POST['watermark_percentage']) and (1 <= $_POST['watermark_percentage'] && $_POST['watermark_percentage'] <= 100),
								'error_msg'	=> _s('Invalid watermark percentage')
							],
						'watermark_opacity' =>
							[
								'validate' 	=> is_numeric($_POST['watermark_opacity']) and (1 <= $_POST['watermark_opacity'] && $_POST['watermark_opacity'] <= 100),
								'error_msg'	=> _s('Invalid watermark opacity')
							],
						'theme'	=>
							[
								'validate'	=> file_exists(G_APP_PATH_THEMES . $_POST['theme']),
								'error_msg'	=> _s('Invalid theme')
							],
						'theme_logo_height' =>
							[
								'validate'	=> G\check_value($_POST['theme_logo_height']) ? is_numeric($_POST['theme_logo_height']) : true,
								'error_msg'	=> _s('Invalid value')
							],
						'theme_tone' =>
							[
								'validate'	=> in_array($_POST['theme_tone'], ['light', 'dark']),
								'error_msg'	=> _s('Invalid theme tone')
							],
						'theme_main_color' =>
							[
								'validate'	=> G\check_value($_POST['theme_main_color']) ? G\is_valid_hex_color($_POST['theme_main_color']) : true,
								'error_mgs'	=> _s('Invalid theme main color')
							],
						'theme_top_bar_color' =>
							[
								'validate'	=> in_array($_POST['theme_top_bar_color'], ['black', 'white']),
								'error_mgs'	=> _s('Invalid theme top bar color')
							],
						'theme_top_bar_button_color' =>
							[
								'validate'	=> in_array($_POST['theme_top_bar_button_color'], CHV\getSetting('available_button_colors')),
								'error_mgs'	=> _s('Invalid theme top bar button color')
							],
						'theme_image_listing_sizing' =>
							[
								'validate'	=> in_array($_POST['theme_image_listing_sizing'], ['fluid', 'fixed']),
								'error_msg'	=> _s('Invalid theme image listing size')
							],
						'theme_home_uids' =>
							[
								'validate'	=> !empty($_POST['theme_home_uids']) ? preg_match('/^[0-9]+(,[0-9]+)*$/', $_POST['theme_home_uids']) : true,
								'error_msg'	=> _s('Invalid user id')
							],
						'email_mode'		=>
							[
								'validate'	=> in_array($_POST['email_mode'], ['smtp', 'phpmail']),
								'error_msg'	=> _s('Invalid email mode')
							],
						'email_smtp_server_port' =>
							[
								'validate'	=> in_array($_POST['email_smtp_server_port'], [25, 80, 465, 587]),
								'error_msg'	=> _s('Invalid SMTP port')
							],
						'email_smtp_server_security'	=>
							[
								'validate'	=> in_array($_POST['email_smtp_server_security'], ['tls', 'ssl', 'unsecured']),
								'error_msg'	=> _s('Invalid SMTP security')
							],
						'website_mode' =>
							[
								'validate'	=> in_array($_POST['website_mode'], ['community', 'personal']),
								'error_msg'	=> _s('Invalid website mode')
							],
						'website_mode_personal_uid' =>
							[
								'validate'	=> $_POST['website_mode'] == 'personal' ? is_numeric($_POST['website_mode_personal_uid']) : TRUE,
								'error_msg'	=> _s('Invalid personal mode user ID')
							],
						'website_mode_personal_routing' =>
							[
								'validate'	=> $_POST['website_mode'] == 'personal' ? !G\is_route_available($_POST['website_mode_personal_routing']) : TRUE,
								'error_msg'	=> _s('Invalid or reserved route')
							],	
						'website_privacy_mode' =>
							[
								'validate'	=> in_array($_POST['website_privacy_mode'], ['public', 'private']),
								'error_msg'	=> _s('Invalid website privacy mode')
							],
						'website_content_privacy_mode'	=>
							[
								'validate'	=> in_array($_POST['website_content_privacy_mode'], ['default', 'private', 'private_but_link']),
								'error_msg'	=> _s('Invalid website content privacy mode')
							],
						'homepage_style' =>
							[
								'validate'	=> in_array($_POST['homepage_style'], ['landing', 'split', 'route_explore']),
								'error_msg'	=> _s('Invalid homepage style')
							],
						'homepage_cta_color' =>
							[
								'validate'	=> in_array($_POST['homepage_cta_color'], CHV\getSetting('available_button_colors')),
								'error_mgs'	=> _s('Invalid homepage call to action button color')
							],
						'homepage_cta_fn' =>
							[
								'validate'	=> $_POST['homepage_style'] == 'route_explore' ? TRUE : in_array($_POST['homepage_cta_fn'], ['cta-upload', 'cta-link']),
								'error_mgs'	=> _s('Invalid homepage call to action functionality')
							],
					];
					
					$bool_validations = array_merge(['auto_language', 'guest_uploads', 'error_reporting', 'maintenance', 'cloudflare', 'recaptcha', 'cdn'], CHV\Login::getSocialServices(['flat' => true]));
					foreach($bool_validations as $v) {
						$validations[$v] = ['validate' => in_array($_POST[$v], [0,1]) ? true : false];
					}
					
					// Validate image path
					if($_POST['upload_image_path']) {
						$safe_upload_image_path = rtrim(G\sanitize_relative_path($_POST['upload_image_path']), '/');
						$image_path = G_ROOT_PATH . $_POST['upload_image_path'];
						if(!file_exists($image_path)) {
							$validations['upload_image_path'] = [
								'validate'	=> false,
								'error_msg' => _s('Invalid upload image path')
							];
						}
					}
					
					// Validate CTA url
					if($_POST['homepage_style'] !== 'route_explore' and $_POST['homepage_cta_fn'] == 'cta-link' and !G\is_url($_POST['homepage_cta_fn_extra'])) {
						if(!empty($_POST['homepage_cta_fn_extra'])) {
							// Sanitize the fn_extra
							$_POST['homepage_cta_fn_extra'] = G\get_regex_match(CHV\getSetting('routing_regex'), '#', $_POST['homepage_cta_fn_extra'], 1);
						} else {
							$validations['homepage_cta_fn_extra'] = [
								'validate'	=> false,
								'error_msg' => _s('Invalid call to action URL')
							];
						}
						
					}
					
					// Validate max size
					foreach(['upload_max_filesize_mb', 'user_image_avatar_max_filesize_mb', 'user_image_background_max_filesize_mb'] as $k) {
						unset($error_max_filesize);
						if(isset($_POST[$k])) {
							if(!is_numeric($_POST[$k]) or $_POST[$k] == 0) {
								$error_max_filesize = _s('Invalid value');
							} else {
								if(G\get_bytes($_POST[$k].'mb') > G\get_ini_bytes(ini_get('upload_max_filesize'))) {
									$error_max_filesize = _s('Max. allowed %s', G\format_bytes(G\get_ini_bytes(ini_get('upload_max_filesize'))));
								}
							}
							$validations[$k] = ['validate' => isset($error_max_filesize) ? false : true, 'error_msg' => $error_max_filesize];
						}
					}
					
					// Handle disabled languages
					if($_POST['languages_enable']) {
						
						// Push default language
						if(!in_array($_POST['default_language'], $_POST['languages_enable'])) {
							$_POST['languages_enable'][] = $_POST['default_language'];
						}
						
						$enabled_languages = [];
						$disabled_languages = CHV\get_available_languages();
						$_POST['languages_disable'] = [];
						foreach($_POST['languages_enable'] as $k) {
							if(!array_key_exists($k, CHV\get_available_languages())) continue;
							$enabled_languages[$k] = CHV\get_available_languages()[$k];
							unset($disabled_languages[$k]);
						}
						CHV\l10n::setStatic('disabled_languages', $disabled_languages);
						CHV\l10n::setStatic('enabled_languages', $enabled_languages);
						unset($_POST['languages_enable']);
						foreach($disabled_languages as $k => $v) {
							$_POST['languages_disable'][] = $k;
						}
						$_POST['languages_disable'] = implode(',', $_POST['languages_disable']);
					}
					
					// Handle personal mode change
					if($_POST['website_mode'] == 'personal' and $_POST['website_mode_personal_routing']) {
						if($logged_user['id'] == $_POST['website_mode_personal_uid']) {
							$new_user_url =  G\get_base_url($_POST['website_mode_personal_routing'] !== '/' ? $_POST['website_mode_personal_routing'] : NULL);
							CHV\Login::setUser('url', G\get_base_url($_POST['website_mode_personal_routing'] !== '/' ? $_POST['website_mode_personal_routing'] : NULL));
							CHV\Login::setUser('url_albums', CHV\User::getUrlAlbums(CHV\Login::getUser()['url']));
						} else if(!CHV\User::getSingle($_POST['website_mode_personal_uid'])) { // Is a valid user id anyway?
							$validations['website_mode_personal_uid'] = [
								'validate' => FALSE,
								'error_msg'=> _s('Invalid personal mode user ID')
							];
						}
					}
					
					// Validate image upload
					foreach(['logo_vector', 'logo_image', 'logo_vector_homepage', 'logo_image_homepage', 'favicon_image', 'watermark_image', 'homepage_cover_image'] as $k) {
						if($_FILES[$k]['tmp_name']) {
							try {
								CHV\upload_to_content_images($_FILES[$k], $k);
							} catch(Exception $e) {
								$validations[$k] = [
									'validate' => false,
									'error_msg' => $e->getMessage()
								];
							}
						}
					}
					
					// Validate SMTP credentials
					if($_POST['email_mode'] == 'smtp') {
						$email_smtp_validate = [
							'email_smtp_server' 			=> _s('Invalid SMTP server'),
							'email_smtp_server_username'	=> _s('Invalid SMTP username'),
							//'email_smtp_server_password'	=> _s('Invalid SMTP password')
						];
						foreach($email_smtp_validate as $k => $v) {
							$validations[$k] = ['validate' => $_POST[$k] ? true : false, 'error_msg' => $v];
						}
						
						$email_validate = ['email_smtp_server', 'email_smtp_server_port', 'email_smtp_server_username', /*'email_smtp_server_password',*/ 'email_smtp_server_security'];
						$email_error = false;
						foreach($email_validate as $k) {
							if(!$validations[$k]['validate']) {
								$email_error = true;
							}
						}

						if(!$email_error) {
							try {
								$mail = new Mailer();
								$mail->SMTPAuth = true;
								$mail->SMTPSecure = $_POST['email_smtp_server_security'];
								$mail->Username = $_POST['email_smtp_server_username'];
								$mail->Password = $_POST['email_smtp_server_password'];
								$mail->Host = $_POST['email_smtp_server'];
								$mail->Port = $_POST['email_smtp_server_port'];
								//$mail->SMTPDebug = 1;
								$valid_mail_credentials = $mail->SmtpConnect();
							} catch(Exception $e) {
							}
							
							if(!$valid_mail_credentials) {
								foreach($email_smtp_validate as $k => $v) {
									$validations[$k]['validate'] = false;
								}
							}
							
						}
						
					}
					
					// Validate social networks
					$social_validate = [
						'facebook'	=> ['facebook_app_id', 'facebook_app_secret'],
						'twitter'	=> ['twitter_api_key', 'twitter_api_secret'],
						'google'	=> ['google_client_id', 'google_client_secret'],
					];
					foreach($social_validate as $k => $v) {
						if($_POST[$k] == 1) {
							foreach($v as $vv) {
								$validations[$vv] = ['validate' => $_POST[$vv] ? true : false];
							}
						}
					}
					
					// Validate CDN
					if($_POST['cdn'] == 1) {
						$cdn_url = trim($_POST['cdn_url'], '/') . '/';
						if(!G\is_url($cdn_url)) {
							$cdn_url = 'http://' . $cdn_url;
						}
						if(!G\is_url($cdn_url) and !G\is_valid_url($cdn_url)) {
							$validations['cdn_url'] = [
								'validate' => false,
								'error_msg' => _s('Invalid URL')
							];
						} else {
							$_POST['cdn_url'] = $cdn_url;
							$handler::updateVar('safe_post', ['cdn_url' => $cdn_url]);
						}
					}
					
					// Validate recaptcha
					if($_POST['recaptcha'] == 1) {
						foreach(['recaptcha_public_key', 'recaptcha_private_key'] as $v) {
							$validations[$v] = ['validate' => $_POST[$v] ? true : false];
						}
					}
					
					// Run the thing
					foreach($_POST + $_FILES as $k => $v) {
						if(isset($validations[$k]) and !$validations[$k]['validate']) {
							$input_errors[$k] = $validations[$k]['error_msg'] ? $validations[$k]['error_msg'] : _s('Invalid value');
						}
					}
					
					if(count($input_errors) == 0) {
						$update_settings = [];
						foreach(CHV\getSettings() as $k => $v) {
							if(isset($_POST[$k]) and $_POST[$k] != (is_bool(CHV\getSetting($k)) ? (CHV\getSetting($k) ? 1 : 0) : CHV\getSetting($k))) {
								$update_settings[] = ['name' => $k, 'value' => $_POST[$k]];
							}
						}
						$db = CHV\DB::getInstance();
						$db->beginTransaction();
						$db->query('UPDATE '.CHV\DB::getTable('settings').' SET setting_value = :value WHERE setting_name = :name');
						foreach($update_settings as $k => $v) {
							$db->bind(':name', $v['name']);
							$db->bind(':value', $v['value']);
							$db->exec();
						}
						if($db->endTransaction()) {
							$is_changed = true;
							$reset_notices = false;
							$settings_to_vars = [
								'website_doctitle' => 'doctitle',
								'website_description' => 'meta_description',
								'website_keywords'=> 'meta_keywords'
							];
							foreach($update_settings as $k => $v) {
								CHV\Settings::setValue($v['name'], $v['value']);
								if($v['name'] == 'maintenance') {
									$reset_notices = true;
								}
								if(array_key_exists($v['name'], $settings_to_vars)) {
									$handler::setVar($settings_to_vars[$v['name']], CHV\getSetting($v['name']));
								}
							}
							if($reset_notices) {
								$system_notices = CHV\getSystemNotices();
								$handler::setVar('system_notices', $system_notices);
							}
						}
					} else {
						$is_error = true;
					}

				}
				
			break;
			
			case 'images':
			case 'albums':
			case 'users':
				switch($doing) {
					case 'images':
						$tabs = [
							[
								'list'		=> true,
								'tools'		=> true,
								'label'		=> _s('Most recent'),
								'id'		=> 'list-most-recent',
								'params'	=> 'list=images&sort=date_desc&page=1',
								'current'	=> $_REQUEST['sort'] == 'date_desc' or !$_REQUEST['sort'] ? true : false,
							],
							[
								'list'		=> true,
								'tools'		=> true,
								'label'		=> _s('Oldest'),
								'id'		=> 'list-most-oldest',
								'params'	=> 'list=images&sort=date_asc&page=1',
								'current'	=> $_REQUEST['sort'] == 'date_asc',
							],
							[
								'list'		=> true,
								'tools'		=> true,
								'label'		=> _s('Most viewed'),
								'id'		=> 'list-most-viewed',
								'params'	=> 'list=images&sort=views_desc&page=1',
								'current'	=> $_REQUEST['sort'] == 'views_desc',
							]
						];
					break;
					
					case 'albums':
						$tabs = [
							[
								'list'		=> true,
								'tools'		=> true,
								'label'		=> _s('Most recent'),
								'id'		=> 'list-most-recent',
								'params'	=> 'list=albums&sort=date_desc&page=1',
								'current'	=> $_REQUEST['sort'] == 'date_desc' or !$_REQUEST['sort'] ? true : false,
							],
							[
								'list'		=> true,
								'tools'		=> true,
								'label'		=> _s('Oldest'),
								'id'		=> 'list-most-oldest',
								'params'	=> 'list=albums&sort=date_asc&page=1',
								'current'	=> $_REQUEST['sort'] == 'date_asc',
							]
						];
					break;
					
					case 'users':
						$tabs = [
							[
								'list'		=> true,
								'tools'		=> false,
								'label'		=> _s('Top users'),
								'id'		=> 'list-top-users',
								'params'	=> 'list=users&sort=image_count_desc&page=1',
								'current'	=> $_REQUEST['sort'] == 'image_count_desc' or !$_REQUEST['sort'] ? true : false,
							],
							[
								'list'		=> true,
								'tools'		=> false,
								'label'		=> _s('Most recent'),
								'id'		=> 'list-most-recent',
								'params'	=> 'list=users&sort=date_desc&page=1',
								'current'	=> $_REQUEST['sort'] == 'date_desc',
							],
							[
								'list'		=> true,
								'tools'		=> false,
								'label'		=> _s('Oldest'),
								'id'		=> 'list-most-oldest',
								'params'	=> 'list=users&sort=date_asc&page=1',
								'current'	=> $_REQUEST['sort'] == 'date_asc',
							]
						];
					break;
				}
				
				$type = $doing;
				$current = false;
				foreach($tabs as $k => $v) {
					if($v['current']) {
						$current = $k;
					}
					$tabs[$k]['type'] = $type;
					$tabs[$k]['url'] = G\get_base_url('dashboard/'.$type.'/?' . $tabs[$k]['params']);
				}
				if(!$current) {
					$current = 0;
					$tabs[0]['current'] = true;
				}
				
				// Use CHV magic params
				$list_params = CHV\Listing::getParams();
				parse_str($tabs[$current]['params'], $tab_params);		
				preg_match('/(.*)_(asc|desc)/', !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : $tab_params['sort'], $sort_matches);
				$list_params['sort'] = array_slice($sort_matches, 1);
				
				
				
				$list = new CHV\Listing;
				$list->setType($type); // images | users | albums
				$list->setOffset($list_params['offset']);
				$list->setLimit($list_params['limit']); // how many results?
				$list->setItemsPerPage($list_params['items_per_page']); // must
				$list->setSortType($list_params['sort'][0]); // date | size | views
				$list->setSortOrder($list_params['sort'][1]); // asc | desc
				$list->setRequester($logged_user );
				$list->output_tpl = $type;
				$list->exec();
				
			break;
			
		}
		
		$handler::setVar('pre_doctitle', _s('Dashboard'));
		
		$handler::setCond('error', $is_error);
		$handler::setCond('changed', $is_changed);
		
		$handler::setVar('error', $error_message);
		$handler::setVar('input_errors', $input_errors);
		$handler::setVar('changed_message', $changed_message);
		
		if($tabs) {
			$handler::setVar('sub_tabs', $tabs);
		}
		if($list) {
			$handler::setVar('list', $list);
		}
		
	} catch(Exception $e) {
		G\exception_to_error($e);
	}
};