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
  
  # This file is used to load G and your G APP
  # If you need to hook elements to this loader you can add them in loader-hook.php

namespace CHV;
use G, Exception;

if(!defined('access') or !access) die('This file cannot be directly accessed.');

setlocale(LC_ALL, 'en_US.UTF8');

// settings.php workaround
if(!is_readable(dirname(__FILE__) . '/settings.php')) {
	if(!@fopen(dirname(__FILE__) . '/settings.php', 'w')) {
		die("Chevereto can't create the app/settings.php file. You must manually create this file.");
	}
}

// G thing
(file_exists(dirname(dirname(__FILE__)) . '/lib/G/G.php')) ? require_once(dirname(dirname(__FILE__)) . '/lib/G/G.php') : die("Can't find lib/G/G.php");

// CHV\DB instance
// CHV\Settings Instance
try {
	if(G\settings_has_db_info()) {
		DB::getInstance();
	}
	Settings::getInstance();
} catch(Exception $e) {
	if(access !== 'install') {
		G\exception_to_error($e);
	}
}

// Set some hard constants
define('CHV_MAX_INVALID_REQUESTS_PER_DAY', 25);

// Folders definitions
define("CHV_FOLDER_IMAGES", !is_null(Settings::get('chevereto_version_installed')) ? Settings::get('upload_image_path') : 'images');

// CHV APP path definitions
define('CHV_APP_PATH_INSTALL', G_APP_PATH . 'install/');
define('CHV_APP_PATH_CONTENT', G_APP_PATH . 'content/');
define('CHV_APP_PATH_LIB_VENDOR', G_APP_PATH . 'vendor/');
define('CHV_APP_PATH_SYSTEM', CHV_APP_PATH_CONTENT . 'system/');
define('CHV_APP_PATH_LANGUAGES', CHV_APP_PATH_CONTENT . 'languages/');

// CHV paths
define('CHV_PATH_IMAGES', G_ROOT_PATH . CHV_FOLDER_IMAGES . '/');
define('CHV_PATH_CONTENT', G_ROOT_PATH . 'content/');
define('CHV_PATH_CONTENT_IMAGES_SYSTEM', CHV_PATH_CONTENT . 'images/system/');
define('CHV_PATH_CONTENT_IMAGES_USERS', CHV_PATH_CONTENT . 'images/users/');
define('CHV_PATH_PEAFOWL', G_ROOT_LIB_PATH . 'Peafowl/');

if(Settings::get('cdn')) {
	define('CHV_ROOT_CDN_URL', Settings::get('cdn_url'));
}
define('CHV_ROOT_URL_STATIC', defined('CHV_ROOT_CDN_URL') ? CHV_ROOT_CDN_URL : G_ROOT_URL);

// Define the app theme
if(!defined('G_APP_PATH_THEME')) {
	$theme_path = G_APP_PATH_THEMES . Settings::get('theme') . '/';
	if(file_exists($theme_path)) {
		define('G_APP_PATH_THEME', $theme_path);
		define('BASE_URL_THEME', G\absolute_to_url(G_APP_PATH_THEME, CHV_ROOT_URL_STATIC));
	}
}

// Set some url paths
define('CHV_URL_PEAFOWL', G\absolute_to_url(CHV_PATH_PEAFOWL, CHV_ROOT_URL_STATIC));

// libssh2-compatibility-layer
if(file_exists(CHV_APP_PATH_LIB_VENDOR . 'phpseclib')) {
	set_include_path(get_include_path() . PATH_SEPARATOR . CHV_APP_PATH_LIB_VENDOR . 'phpseclib');
}

// Always test the current installation
(file_exists(G_APP_PATH_LIB . 'integrity-check.php')) ? require_once G_APP_PATH_LIB . 'integrity-check.php' : die("Can't find app/lib/integrity-check.php");
check_system_integrity();

if(access !== 'install' and Settings::get('chevereto_version_installed')) {
	// Error reporting by DB config
	if(Settings::get('error_reporting') === false) {
		error_reporting(0);
	}
	// Set the default timezone by DB config
	if(G\is_valid_timezone(Settings::get('default_timezone'))) {
		date_default_timezone_set(Settings::get('default_timezone'));
	}
	// Cloudflare REMOTE_ADDR workaround
	if(Settings::get('cloudflare') or isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
		if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
			$cloudflare = 1;
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} else {
			$cloudflare = 0;
		}
		// Inject CF setting
		if(Settings::get('cloudflare') !== (bool) $cloudflare) {
			DB::update('settings', ['value' => $cloudflare], ['name' => 'cloudflare']);
		}
	}
}

if(array_key_exists('queue', $_REQUEST) and $_REQUEST['r']) {
	Queue::process(['type' => 'storage-delete']);
}

// User login handle
if(Settings::get('chevereto_version_installed')) {
	try {
		if($_SESSION['login']) {
			Login::login($_SESSION['login']['id'], $_SESSION['login']['type']);
		} else if($_COOKIE['KEEP_LOGIN']) {
			Login::loginCookie('internal');
		} else if($_COOKIE['KEEP_LOGIN_SOCIAL']) {
			Login::loginCookie('social');
		}
		if(!Login::isLoggedUser()) {
			//Login::logout(); //  why?
		} else {
			// Set the timezone for the logged user
			if(Login::getUser()['timezone'] !== Settings::get('default_timezone') and G\is_valid_timezone(Login::getUser()['timezone'])) {
				date_default_timezone_set(Login::getUser()['timezone']);
			}
		}
	} catch(Exception $e) {
		Login::logout();
		G\exception_to_error($e);
	}
}

// Language localization
(file_exists(G_APP_PATH_LIB . 'l10n.php')) ? require_once(G_APP_PATH_LIB . 'l10n.php') : die("Can't find app/lib/l10n.php");

// Not installed
if(!Settings::get('chevereto_version_installed')) {
	new G\Handler([
		'before' => function($handler) {
			if($handler->request_array[0] !== 'install') {
				G\redirect('install');
			}
		}
	]);
}

// Translate logged user count labels
if(Login::isLoggedUser()) {
	foreach(['image_count_label', 'album_count_label'] as $v) {
		Login::$logged_user[$v] = _s(Login::$logged_user[$v]);
	}
}

// Handle banned IP address
if(version_compare(Settings::get('chevereto_version_installed'), '3.5.14', '>=')) {
	$banned_ip = Ip_ban::getSingle();
	if($banned_ip) {
		if(G\is_url($banned_ip['message'])) {
			G\redirect($banned_ip['message']);
		} else {
			die(empty($banned_ip['message']) ? _s('You have been forbidden to use this website.') : $banned_ip['message']);
		}
	}
}

// Append any app loader hook (user own hooks)
if(file_exists(G_APP_PATH . 'chevereto-hook.php')) {
	require_once(G_APP_PATH . 'chevereto-hook.php');
}

// Fix the default system images (must be done here because CHV_PATH_CONTENT_IMAGES_SYSTEM)
foreach([
	'favicon_image'			=> 'favicon.png',
	'logo_vector'			=> 'logo.svg',
	'logo_image'			=> 'logo.png',
	'watermark_image'		=> 'watermark.png',
	'homepage_cover_image'	=> 'home_cover.jpg',
	'logo_vector_homepage'	=> 'logo_homepage.svg',
	'logo_image_homepage'	=> 'logo_homepage.png'
] as $k => $v) {
	if(!G\check_value(Settings::get($k)) or !file_exists(CHV_PATH_CONTENT_IMAGES_SYSTEM . Settings::get($k))) {
		$value = 'default/' . $v;
		if(in_array($k, ['logo_vector_homepage', 'logo_image_homepage'])) {
			$no_homepage_value = Settings::get(G\str_replace_last('_homepage', NULL, $k));
			if(!G\starts_with('default/', $no_homepage_value)) {
				$value = $no_homepage_value;
			}
		}
		Settings::setValue($k, $value);
	}
}

// We're getting fancy
try {
	if(!isset($hook_before)) {
		$hook_before = function($handler) {
			$base = $handler::$base_request;
			
			$is_admin = (bool)Login::getUser()['is_admin'];
			
			// Inject some global binds
			$handler::setVar('auth_token', $handler::getAuthToken());
			$handler::setVar('doctitle', getSetting('website_name'));
			$handler::setVar('meta_description', getSetting('website_description'));
			$handler::setVar('meta_keywords', getSetting('website_keywords'));
			$handler::setVar('logged_user', Login::getUser());
			$handler::setVar('failed_access_requests', 0); // Init
			$handler::setCond('admin', $is_admin);
			$handler::setCond('maintenance', getSetting('maintenance') and !Login::getUser()['is_admin']);
			$handler::setCond('captcha_needed', getSetting('recaptcha') and getSetting('recaptcha_threshold') == 0);
			
			// Login if maintenance /dashboard
			if($handler::getCond('maintenance') and $handler->request_array[0] == 'dashboard') {
				G\redirect('login');
			}
			
			// reCaptcha thing (only non logged users)
			if(!Login::getUser()) {
				$failed_access_requests = Requestlog::getCounts(['login', 'signup'], 'fail');
				if(getSetting('recaptcha') and $failed_access_requests['day'] > getSetting('recaptcha_threshold')) {
					$handler::setCond('captcha_needed', true);
				}
				$handler::setVar('failed_access_requests', $failed_access_requests);
			}
			
			// Personal mode
			if(getSetting('website_mode') == 'personal') {
				
				// Disable some stuff for the rest of the mortals
				if(!$handler::getVar('logged_user')['is_admin']) {
					//Settings::setValue('website_explore_page', FALSE);
					//Settings::setValue('website_search', FALSE);
				}
				
				parse_str($_SERVER['QUERY_STRING'], $querystr);
				// Keep ?random & ?lang when route is /
				if($handler->request_array[0] == '/' and getSetting('website_mode_personal_routing') == '/' and in_array(key($querystr), ['random', 'lang'])) {
					$handler->mapRoute('index');
				// Keep /search/something (global search) when route is /
				} else if($handler->request_array[0] == 'search' and in_array($handler->request_array[1], ['images', 'albums', 'users'])) {
					$handler->mapRoute('search');
				// Map user for base routing + sub-routes
				} else if($handler->request_array[0] == getSetting('website_mode_personal_routing') or (getSetting('website_mode_personal_routing') == '/' and in_array($handler->request_array[0], ['albums', 'search']))) {
					$handler->mapRoute('user', [
						'id' => getSetting('website_mode_personal_uid')
					]);
				}
				
				// Inject some stuff for the index page
				if($handler->request_array[0] == '/' and !in_array(key($querystr), ['random', 'lang']) and !$handler::getCond('mapped_route')) {
					$personal_mode_user = User::getSingle(getSetting('website_mode_personal_uid'));
					if(Settings::get('homepage_title_html') == NULL) {
						Settings::setValue('homepage_title_html', $personal_mode_user['name']);
					}
					if(Settings::get('homepage_paragraph_html') == NULL) {
						Settings::setValue('homepage_paragraph_html', _s('Feel free to browse and discover all my shared images and albums.'));
					}
					if(Settings::get('homepage_cta_html') == NULL) {
						Settings::setValue('homepage_cta_html', _s('View all my images'));
					}
					if(Settings::get('homepage_cta_fn') !== 'cta-link') {
						Settings::setValue('homepage_cta_fn', 'cta-link');
						Settings::setValue('homepage_cta_fn_extra', $personal_mode_user['url']);
					}
					if($personal_mode_user['background']['url']) {
						Settings::setValue('homepage_cover_image', $personal_mode_user['background']['url']);
					}
				}
				
			} else { // Community mode
				
				if($base !== 'index' and !G\is_route_available($handler->request_array[0])) {
					if(getSetting('user_routing')) {
						$handler->mapRoute('user');
					} else {
						$image_id = decodeID($base);
						$image = Image::getSingle($image_id, false, true);
						if($image) {
							G\redirect($image['url_viewer'], 301);
						}
					}
				}
			}		
			
			// Website privacy mode
			if(getSetting('website_privacy_mode') == 'private' and !Login::getUser()) {
				$allowed_requests = ['api', 'login', 'logout', 'image', 'album', 'page', 'account', 'connect'];
				if(getSetting('enable_signups')) {
					$allowed_requests[] = 'signup';
				}
				if(!in_array($handler->request_array[0], $allowed_requests)) {
					G\redirect('login');
				}
			}
			
			// Private gate
			$handler::setCond('private_gate', getSetting('website_privacy_mode') == 'private' and !Login::getUser());
			
			// Forced privacy
			$handler::setCond('forced_private_mode', (getSetting('website_privacy_mode') == 'private' and getSetting('website_content_privacy_mode') !== 'default'));
			
			// Categories
			$categories = [];
			try {
				$categories_db = DB::queryFetchAll('SELECT * FROM ' . DB::getTable('categories') . ' ORDER BY category_name ASC;');
				if(count($categories_db) > 0) {
					foreach($categories_db as $k => $v) {
						$key = $v['category_id'];
						$categories[$key] = $v;
						$categories[$key]['category_url'] = G\get_base_url('category/' . $v['category_url_key']);
						$categories[$key] = DB::formatRow($categories[$key]);
					}
				}
			} catch (Exception $e) {}
			$handler::setVar('categories', $categories);
			
			// upload allowed conditional
			$upload_allowed = getSetting('enable_uploads');
			if(!Login::getUser()) {
				if(!getSetting('guest_uploads') or getSetting('website_privacy_mode') == 'private' or $handler::getCond('maintenance')) {
					$upload_allowed = false;
				}
			} else {
				if(getSetting('website_mode') == 'personal' and getSetting('website_mode_personal_uid') !== Login::getUser()['id']) {
					$upload_allowed = false;
				}
			}
			if(Login::getUser()['is_admin']) {
				$upload_allowed = true;
			}
			$handler::setCond('upload_allowed', $upload_allowed);
			
			// Maintenance mode
			if(getSetting('maintenance') and !Login::getUser()['is_admin']) {
				$handler::setCond('private_gate', true);
				$allowed_requests = ['login', 'account', 'connect'];
				if(!in_array($handler->request_array[0], $allowed_requests)) {
					$handler->preventRoute('maintenance');
				}
			}
			
			// Inject the system notices
			if($is_admin) {
				$system_notices = getSystemNotices();
			}
			$handler::setVar('system_notices', $system_notices);
			
			if(!in_array($handler->request_array[0], ['login', 'signup', 'account', 'connect', 'logout', 'json', 'api'])) {
				$_SESSION['last_url'] = G\get_current_url();
			}
		};
	}
	if(!isset($hook_after)) {
		$hook_after = function($handler) {
			if($handler->template == 404) {
				unset($_SESSION['last_url']);
				$handler::setVar('doctitle', _s("That page doesn't exist") . ' (404) - ' . getSetting('website_name'));
			}
		};
	}
	new G\Handler(['before' => $hook_before, 'after' => $hook_after]);
	$_SESSION['REQUEST_REFERER'] = G\get_current_url(); // Save in session the current internal request
} catch(Exception $e) {
	G\exception_to_error($e);
}