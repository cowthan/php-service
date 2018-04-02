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
use G, DirectoryIterator, Exception;
  
if(!defined("access") or !access) die("This file cannot be directly accessed.");

// Inspired from http://stackoverflow.com/a/18602474
// L10n version of G\time_elapsed_string
function time_elapsed_string($datetime, $full=false) {
	
	//if()
	
	$now = new \DateTime(G\datetimegmt());
	$ago = new \DateTime($datetime);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = [
		'y' => _s('year'),
		'm' => _s('month'),
		'w' => _s('week'),
		'd' => _s('day'),
		'h' => _s('hour'),
		'i' => _s('minute'),
		's' => _s('second'),
	];
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			
			$times = [
				'y' => _n('year', 'years', $diff->$k),
				'm' => _n('month', 'months', $diff->$k),
				'w' => _n('week', 'weeks', $diff->$k),
				'd' => _n('day', 'days', $diff->$k),
				'h' => _n('hour', 'hours', $diff->$k),
				'i' => _n('minute', 'minutes', $diff->$k),
				's' => _n('second', 'seconds', $diff->$k),
			];
			
			$v = $diff->$k . ' ' . $times[$k];
			
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	
	return count($string) > 0 ? _s('%s ago', implode(', ', $string)) : _s('moments ago');

}

function missing_values_to_exception($object, $exception=Exception, $values_array, $code=100) {
	if(!is_object($object)) return;
	for($i=0; $i<count($values_array); $i++) {
		if(!G\check_value($object->$values_array[$i])) {
			throw new $exception('Missing $'.$values_array[$i], ($code+$i));
			break;
		}
	}
}

function send_mail($to, $subject, $body) {
	
	$own_name = __FUNCTION__ . '()';
	
	$args = ['to', 'subject', 'body'];
	
	foreach(func_get_args() as $k => $v) {
		if(!$v) {
			throw new Exception('Missing $'.$args[$k].' in '. $own_name);
		}
	}
	if(!filter_var($to, FILTER_VALIDATE_EMAIL)) {
		throw new Exception('Invalid email in ' . $own_name);
	}
	foreach(['email_from_email', 'email_from_name'] as $v) {
		if(!getSettings()[$v]) {
			throw new Exception('Invalid $'.$v.' setting in ' . $own_name);
		}
	}
	
	try {
		$mail = new \Mailer();
		$mail->CharSet = 'UTF-8';
		if($body != strip_tags($body)) {
			$mail->IsHTML(true);
		}
		$mail->Mailer = getSettings()['email_mode'];
		if($mail->Mailer == 'smtp') {
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = getSettings()['email_smtp_server_security'];
			$mail->Port	= getSettings()['email_smtp_server_port'];
			$mail->Host = getSettings()['email_smtp_server'];
			$mail->Username = getSettings()['email_smtp_server_username']; 
			$mail->Password = getSettings()['email_smtp_server_password'];
		}
		$mail->Timeout = 30;
		
		$mail->Subject = $subject;
		$mail->Body = $body;
		
		$mail->addAddress($to);
		//$mail->addReplyTo($email);
		
		$mail->setFrom(getSettings()['email_from_email'], getSettings()['email_from_name']);
		
		if($mail->Send()) {
			return true;
		} else {
			throw new Exception($mail->DbgOut, 300);
		}
	} catch (Exception $e) {
		throw new Exception($e->getMessage(), $e->getCode());
	}
	
}

/**
 * GET AND FETCH SOME DATA
 * ----------------------------------------------------------------------------------------------------------------------------------------
 */

function get_chevereto_version($full=true) {
	return G\get_app_version($full);
}

// deprecate
function getSettings($safe=false) {
	$settings = Settings::get();
	return $safe ? G\safe_html($settings) : $settings;
}
// deprecate
function get_chv_default_settings($safe=false) {
	$defaults = Settings::getDefaults();
	return $safe ? G\safe_html($defaults) : $defaults;
}
// deprecate
function getSetting($value='', $safe=false) {
	$return = getSettings()[$value];
	return $safe ? G\safe_html($return) : $return;
}
// deprecate
function get_chv_default_setting($value='', $safe=false) {
	$return = get_chv_default_settings()[$value];
	return $safe ? G\safe_html($return) : $return;
}

function getStorages() {
	$storages = DB::get('storages', 'all');
	if($storages) {
		foreach($storages as $k => $v) {
			$storages[$k] = DB::formatRow($v);
		}
		$return = $storages;
	} else {
		$return = false;
	}
	return $return;
}

function get_banner_code($banner, $safe=true) {
	if(strpos($banner, 'banner_') !== 0) {
		$banner = 'banner_' . $banner;
	};
	$banner_code = Settings::get($banner);
	if($safe) {
		$banner_code = G\safe_html($banner_code);
	}
	if($banner_code) {
		return $banner_code;
	}
}

function getSystemNotices() {
	$system_notices = [];
	if(version_compare(G_APP_VERSION, getSetting('chevereto_version_installed'), '>')) {
		$system_notices[] = _s('System database is outdated. You need to run the <a href="%s">update</a> tool.', G\get_base_url('install'));
	}
	if(getSetting('maintenance')) {
		$system_notices[] = _s('Website is in maintenance mode. To revert this setting go to <a href="%s">Dashboard > Settings</a>.', G\get_base_url('dashboard/settings/system'));
	}
	// Just for production
	if(!in_array($_SERVER['HTTP_HOST'], ['127.0.0.1', 'demo.example.com'])) {
		if(preg_match('/@example\.com$/', getSetting('email_from_email')) or preg_match('/@example\.com$/', getSetting('email_incoming_email'))) {
			$system_notices[] = _s("You haven't changed the default email settings. Go to <a href='%s'>Dashboard > Settings > Email</a> to fix this.", G\get_base_url('dashboard/settings/email'));
		}
	}
	return $system_notices;
}

function hashed_token_info($public_token_format) {
	$explode = explode(":", $public_token_format);
	return array(
		"id"		 => decodeID($explode[0]),
		"id_encoded" => $explode[0],
		"token"		 => $explode[1]
	);
}

function generate_hashed_token($id, $token="") {
	$token = G\random_string(rand(128, 256));
	$hash = password_hash($token, PASSWORD_BCRYPT);
	return array(
		"token"					=> $token,
		"hash"					=> $hash,
		"public_token_format"	=> encodeID($id) . ':' . $token
	);
}

function check_hashed_token($hash, $public_token_format) {	
	$public_token = hashed_token_info($public_token_format);
	return password_verify($public_token["token"], $hash);
}

function recaptcha_check() {

	// Detect reCaptcha version
	if(preg_match('/[-_]+/', getSetting('recaptcha_public_key'))) { // new one
		$endpoint = 'https://www.google.com/recaptcha/api/siteverify';
		$params = [
			'secret'	=> getSetting('recaptcha_private_key'),
			'response'	=> $_POST['g-recaptcha-response'],
			'remoteip'	=> G\get_client_ip()
		];
		
		$endpoint .= '?' . http_build_query($params);
		$re_api = json_decode(G\fetch_url($endpoint));
		// Mimic old reCaptcha API return
		return (object)['is_valid' => (bool)$re_api->success];
	} else {
		$re = array(
			'private_key'	=> getSetting('recaptcha_private_key'),
			'ip'			=> G\get_client_ip(),
			'challenge'		=> $_POST['recaptcha_challenge_field'],
			'response'		=> $_POST['recaptcha_response_field']
		);
		require_once(CHV_APP_PATH_LIB_VENDOR . 'recaptchalib.php');
		return recaptcha_check_answer($re['private_key'], $re['ip'], $re['challenge'], $re['response']);
	}
}

function must_use_recaptcha($val, $max="") {
	if($max == "" or !is_int($max)) {
		$db_max = getSetting('recaptcha_threshold');
		$max = isset($db_max) ? $db_max : 5;
	}
	return $val >= $max;
}

function is_max_invalid_request($val, $max='') {
	if($max == '' or !is_int($max)) {
		$max = CHV_MAX_INVALID_REQUESTS_PER_DAY;
	}
	return $val > $max;
}

// BCMath workaroud
if (!function_exists('bcdiv')) {
	function bcdiv( $dividend, $divisor ) {
	   $quotient = floor( $dividend/$divisor );
	   return $quotient;
	}
	function bcmod( $dividend, $modulo ) {
	   $remainder = $dividend%$modulo;
	   return $remainder;
	}
	function bcmul( $left, $right ) {
	   return $left * $right;
	}
	function bcadd( $left, $right ) {
	   return $left + $right;
	}
	function bcpow( $base, $power ) {
	   return pow( $base, $power );
	}
}

/**
 * LANGUAGE
 * ----------------------------------------------------------------------------------------------------------------------------------------
 */

function get_translation_table() {
	return L10n::getTranslation();
}
 
function get_language_used() {
	return get_available_languages()[L10n::getStatic('locale')];
}

function get_available_languages() {
	return L10n::getAvailableLanguages();
}

function get_enabled_languages() {
	return L10n::getEnabledLanguages();
}

function get_disabled_languages() {
	return L10n::getDisabledLanguages();
}

/**
 * CRYPT
 * ----------------------------------------------------------------------------------------------------------------------------------------
 */
 
/*
 * cheveretoID
 * Encode/decode an id
 *
 * @author   Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author   Simon Franz
 * @author   Deadfish
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link   http://kevin.vanzonneveld.net/
 *
 * http://kvz.io/blog/2009/06/10/create-short-ids-with-php-like-youtube-or-tinyurl/
 *
 */

function cheveretoID($in, $action="encode") {
	global $cheveretoID;
	$index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$salt = getSetting('crypt_salt');
	
	// Use a stock version of the hashed values (faster execution)
	if(isset($cheveretoID)) {
		$passhash = $cheveretoID['passhash'];
		$p = $cheveretoID['p'];
		$i = $cheveretoID['i'];
	} else {
		
		for($n = 0; $n<strlen($index); $n++) {
			$i[] = substr($index,$n ,1);
		}

		$passhash = hash('sha256',$salt);
		$passhash = (strlen($passhash) < strlen($index)) ? hash('sha512',$salt) : $passhash;

		for($n=0; $n < strlen($index); $n++) {
			$p[] =  substr($passhash, $n ,1);
		}
		
		// Stock the crypting thing to don't do it every time
		$cheveretoID = [
			'passhash'	=> $passhash,
			'p'			=> $p,
			'i'			=> $i
		];
	}
	
	array_multisort($p, SORT_DESC, $i);
	$index = implode($i);

	$base  = strlen($index);
	
	if($action == "decode") {
		// Digital number  <<--  alphabet letter code
		$out = 0;
		$len = strlen($in) - 1;
		for ($t = 0; $t <= $len; $t++) {
		  $bcpow = bcpow($base, $len - $t);
		  $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
		}
		$out = sprintf("%F", $out);
		$out = substr($out, 0, strpos($out, '.'));
		$out = $out;
	} else {
		// Digital number  -->>  alphabet letter code
		$out = "";
		for ($t = floor(log((float)$in, $base)); $t >= 0; $t--) {
			$bcp = bcpow($base, $t);
			$a   = floor($in / $bcp) % $base;
			$out = $out . substr($index, $a, 1);
			$in  = $in - ($a * $bcp);
		}
	}

	return $out;
}
 
// Shorthand for cheveretoID encode
function encodeID($var) {
	return cheveretoID($var, "encode");
}

// Shorthand for cheveretoID decode
function decodeID($var) {
	return cheveretoID($var, "decode");
}


/**
 * Get some URLs
 */
function get_content_url($sub) {
	return G\absolute_to_url(CHV_PATH_CONTENT . $sub, CHV_ROOT_URL_STATIC);
}

function get_system_image_url($filename) {
	return get_content_url('images/system/'.$filename);
}

function get_users_image_url($filename) {
	return get_content_url('images/users/'.$filename);
}

/**
 * Some G\ overrides
 */
function get_image_fileinfo($file) {
	if(G\is_url($file)) {
		$extension = G\get_file_extension($file);
		$info = [
			'filename'	=> basename($file), // image.jpg
			'name'		=> basename($file, '.' . $extension), // image
			'mime'		=> G\extension_to_mime($extension),
			'extension'	=> $extension,
			'url' 		=> $file
		];
	} else {
		$info = G\get_image_fileinfo($file);
		unset($info['bits'], $info['channels']);
		if(defined('CHV_ROOT_URL_STATIC')) {
			$info['url'] = preg_replace('#'.G_ROOT_URL.'#', CHV_ROOT_URL_STATIC, $info['url'], 1);
		}
	}
	return $info;
}

/**
 * Internal uploads
 */
function upload_to_content_images($source, $what) {
	try {
		
		if(!defined('CHV_PATH_CONTENT_IMAGES_SYSTEM')) {
			throw new Exception('Outdated app/loader.php', 100);
		}
		
		if(!file_exists(CHV_PATH_CONTENT_IMAGES_SYSTEM) && !@mkdir(CHV_PATH_CONTENT_IMAGES_SYSTEM, 0755, true)) {
			throw new Exception(sprinf("Target upload directory %s doesn't exists.", G\absolute_to_relative(CHV_PATH_CONTENT_IMAGES_SYSTEM)), 101);
		}
		
		if(!is_writable(CHV_PATH_CONTENT_IMAGES_SYSTEM)) {
			throw new Exception(sprintf("No write permission in %s", G\absolute_to_relative(CHV_PATH_CONTENT_IMAGES_SYSTEM)), 102);
		}
		
		$typeArr = [
			'favicon_image'	=> [
				'name' => 'favicon',
				'type' => 'image'
			],
			'logo_vector'	=> [
				'name' => 'logo',
				'type' => 'file'
			],
			'logo_image'	=> [
				'name' => 'logo',
				'type' => 'image'
			],
			'watermark_image' => [
				'name' => 'watermark',
				'type' => 'image'
			],
			'homepage_cover_image' => [
				'name'	=> 'home_cover',
				'type'	=> 'image'
			]
		];
		foreach(['logo_vector', 'logo_image'] as $k) {
			$typeArr[$k . '_homepage'] = array_merge($typeArr[$k], ['name' => 'logo_homepage']);
		}
		foreach($typeArr as $k => &$v) {
			$v['name'] .= '_' . G\datetimegmt('YmdHis'); // prevent hard cache issues
		}
		
		$name = $typeArr[$what]['name'];

		if($typeArr[$what]['type'] == 'image') {
			
			$fileinfo = @G\get_image_fileinfo($source['tmp_name']);
			
			// Pre-validations
			switch($what) {
				case 'favicon_image':
					if(!$fileinfo['ratio']) {
						throw new Exception('Invalid favicon image.', 200);
					}
					if($fileinfo['ratio'] != 1) {
						throw new Exception('You need to use a square image for the favicon.', 210);
					}
				break;
				case 'watermark_image':
					if($fileinfo['extension'] !== 'png') {
						throw new Exception('Invalid watermark image.', 200);
					}
				break;
			}
			
			$upload = new Upload;
			$upload->setSource($source);
			$upload->setDestination(CHV_PATH_CONTENT_IMAGES_SYSTEM);
			$upload->setFilename($name);
			$upload->exec();
			$uploaded = $upload->uploaded;
			
		} else {
			
			// Check file error
			 switch ($source['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new Exception('No file sent.', 500);
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new Exception('Exceeded filesize limit.', 501);
				default:
					throw new Exception('Unknown errors.', 502);
			}
			
			$file_contents = @file_get_contents($source['tmp_name']);
			if(!$file_contents) {
				throw new Exception("Can't read uploaded file content.", 500);
			}
			
			if(strpos($file_contents, '<!DOCTYPE svg PUBLIC') == false and strpos($file_contents, '<svg') == false) {
				throw new Exception("Uploaded file isn't an SVG.", 300);
			}
			
			$filename = $name . G\random_string(8) . '.svg';
			$destination = CHV_PATH_CONTENT_IMAGES_SYSTEM . $filename;
			
			if(!@move_uploaded_file($source['tmp_name'], $destination)) {
				throw new Exception("Can't move uploaded file to its destination.", 500);
			}
			
			$uploaded = [
				'file' => $destination,
				'filename' => $filename,
				'fileinfo' => [
					'extension' => 'svg',
					'filename' => $filename
				]
			];
		}
		
		$filename = $name . '.' . $uploaded['fileinfo']['extension'];
		$file = str_replace($uploaded['fileinfo']['filename'], $filename, $uploaded['file']);
		
		if(!@rename($uploaded['file'], $file)) {
			throw new Exception("Can't rename uploaded ".$name." file", 500);
		}
		
		$remove_old = true;
		$db_filename = getSetting($what);		
		$db_file = CHV_PATH_CONTENT_IMAGES_SYSTEM . $db_filename;
		
		if(in_array($what, ['logo_vector_homepage', 'logo_image_homepage']) and !G\starts_with('logo_homepage', $db_filename)) {
			$remove_old = false;
		}
		
		if($remove_old and !G\starts_with('default/', $db_filename) and $db_filename != $filename and is_readable($db_file) and !@unlink($db_file)) {
			throw new Exception("Can't remove old ".$name." file", 500);
		}
		
		DB::update('settings', ['value' => $filename], ['name' => $what]);
		
		Settings::setValue($what, $filename);
		
	} catch(Exception $e) {
		throw new Exception($e->getMessage(), $e->getCode());
	}
}