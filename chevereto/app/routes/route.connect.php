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
		$doing = $handler->request[0];
		
		if(!in_array($doing, ['google', 'facebook', 'twitter', 'vk'])) {
			return $handler->issue404();
		}
		
		$logged_user = CHV\Login::getUser();
		
		// User status override redirect
		CHV\User::statusRedirect($logged_user['status']);
		
		// Detect return _REQUEST
		if($_REQUEST['return']) {
			$_SESSION['connect_return'] = $_REQUEST['return'];
		}
		
		if($_SESSION['login']['type'] == $doing) {
			G\redirect($logged_user['url']);
		}
		
		// Forbidden connection
		if(!CHV\getSetting($doing)) {
			return $handler->issue404();
		}
		
		// Require the connect vendor class
		$vendor_autoload = CHV_APP_PATH_LIB_VENDOR . $doing.'/'.$doing.'.php';
		if(!@include_once($vendor_autoload)) {
			throw new Exception("Can't find $doing vendor class", 100);
		}

		$do_connect = false;
		
		switch($doing) {
			
			case 'facebook':

				$facebook = new Facebook([
					'appId'  => CHV\getSetting('facebook_app_id'),
					'secret' => CHV\getSetting('facebook_app_secret')
				]);
		
				$user_id = $facebook->getUser();
				
				if($user_id) {

					$get_user = $facebook->api($user_id);
					
					// Todo, update to PHP SDK 4.X
					// Ugly profile url https://www.facebook.com/app_scoped_user_id/<ID>
					
					$social_pictures = [
						'avatar'		=> 'http://graph.facebook.com/'.$get_user['id'].'/picture/?width=160&height=160',
						'background'	=> $facebook->api('/'.$get_user['id'].'?fields=cover')['cover']['source']
					];

					$connect_user = [
						'id'		=> $get_user['id'],
						'username'	=> G\sanitize_string(G\unaccent_string($get_user['name']), true, true),
						'name'		=> $get_user['name'],
						'avatar'	=> $social_pictures['avatar'],
						'url'		=> $get_user['link'],
						'website'	=> NULL
					];
					$connect_tokens = [
						'secret'	=> $facebook->getAccessToken(),
						'token_hash'=> NULL
					];
					$do_connect = true;
				} else {
					
					// Redirect to home on error
					if(isset($_REQUEST['callback']) and $_REQUEST['error']) {
						G\redirect();
					}
					
					G\redirect($facebook->getLoginUrl(['redirect_uri' => G\get_base_url('connect/facebook/?callback')]));
				}
				
			break;
			
			case 'twitter':
				
				$twitter = [
					'key' 		=> CHV\getSetting('twitter_api_key'),
					'secret'	=> CHV\getSetting('twitter_api_secret')
				];
				
				if($_REQUEST['oauth_verifier'] and $_SESSION['twitter']['oauth_token'] and $_SESSION['twitter']['oauth_token_secret']){
						
					// Handle the twitter callback
					$twitteroauth = new TwitterOAuth($twitter['key'], $twitter['secret'], $_SESSION['twitter']['oauth_token'], $_SESSION['twitter']['oauth_token_secret']);
					$access_token = $twitteroauth->getAccessToken($_REQUEST['oauth_verifier']);
					
					if($access_token) {
						$twitteroauth = new TwitterOAuth($twitter['key'], $twitter['secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
						$get_user = $twitteroauth->get('account/verify_credentials');
						
						if($get_user->errors) {
							G\redirect('connect/'.$doing);
						} else {
							$social_pictures = [
								'avatar'		=> str_replace('_normal.', '.', $get_user->profile_image_url_https),
								'background'	=> $get_user->profile_background_image_url
							];
							$connect_user = [
								'id'		=> $get_user->id,
								'username'	=> $get_user->screen_name,
								'name'		=> $get_user->name,
								'avatar'	=> $social_pictures['avatar'],
								'url'		=> 'http://twitter.com/'.$get_user->screen_name,
								'website'	=> $get_user->entities->url ? $get_user->entities->url->urls[0]->expanded_url : NULL
							];
							$connect_tokens = [
								'secret'	=> $access_token['oauth_token_secret'],
								'token_hash'=> $access_token['oauth_token']
							];
							$do_connect = true;
						}
					} else {
						throw new Exception('Twitter connect error code:'.$twitteroauth->http_code, 400);
					}
				 
				} else {
					
					if(isset($_REQUEST['denied'])) {
						G\redirect();
					}
					
					// Request the twitter login
					$twitteroauth = new TwitterOAuth($twitter['key'], $twitter['secret']);
					$request_token = $twitteroauth->getRequestToken(G\get_base_url('connect/twitter'));

					if($twitteroauth->http_code == 200){
						$_SESSION['twitter']['oauth_token'] = $request_token['oauth_token'];  
						$_SESSION['twitter']['oauth_token_secret'] = $request_token['oauth_token_secret']; 
						$url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
						G\redirect($url);
					} else {
						unset($_SESSION['twitter']);
						throw new Exception('Twitter connect error code:'.$twitteroauth->http_code, 400);
					}  
				}

			break;
			
			case 'google':
				
				$google = [
					'id' 		=> CHV\getSetting('google_client_id'),
					'secret'	=> CHV\getSetting('google_client_secret')
				];
				
				// Validate agains CSRF
				if($_REQUEST['state'] and $_SESSION['google']['state'] !== $_REQUEST['state']) {
					G\set_status_header(403);
					$handler->template = "request-denied";
				} else {
					$_SESSION['google']['state'] = md5(uniqid(mt_rand(), true));
				}
				
				// User cancelled the login flow
				if($_REQUEST['error'] == 'access_denied') {
					G\redirect('login');
				}
				
				$client = new Google_Client();
				$client->setApplicationName(CHV\getSetting('website_name') . ' connect');
				$client->setClientId($google['id']);
				$client->setClientSecret($google['secret']);
				$client->setRedirectUri(G\get_base_url('connect/google'));
				$client->setState($_SESSION['google']['state']);
				$client->setScopes(['https://www.googleapis.com/auth/plus.login']); // https://developers.google.com/+/api/oauth
				
				$plus = new Google_Service_Plus($client);
				
				if(isset($_GET['code'])) {
					$client->authenticate($_GET['code']);
					$_SESSION['google']['token'] = $client->getAccessToken();
				}

				if($_SESSION['google']['token']) {
					$client->setAccessToken($_SESSION['google']['token']);
				}

				if($client->getAccessToken()) {
					$get_user = $plus->people->get('me');
					
					if($get_user) {
						$social_pictures = [
							'avatar'		=> preg_replace('/\?.*/', '', $get_user['image']['url']),
							'background'	=> NULL
						];
						$connect_user = [
							'id'		=> $get_user['id'],
							'username'	=> G\sanitize_string(G\unaccent_string($get_user['displayName']), true, true),
							'name'		=> $get_user['displayName'],
							'avatar'	=> $get_user['image']['url'],
							'url'		=> $get_user['url'],
							'website'	=> preg_match('#https?:\/\/profiles\.google\.com\/.*#', $get_user['urls'][0]['value']) ? NULL : $get_user['urls'][0]['value']
						];
						$google_token = json_decode($client->getAccessToken());
						$connect_tokens = [
							'secret'	=> $client->getAccessToken(),
							'token_hash'=> $google_token->access_token
						];
						$do_connect = true;
					}
				} else {
					G\redirect($client->createAuthUrl());
				}
				
			break;
			
			case 'vk':
				
				$vk = [
					'client_id'		=> CHV\getSetting('vk_client_id'),
					'client_secret'	=> CHV\getSetting('vk_client_secret'),
					'redirect_uri'	=> G\get_base_url('connect/vk')
				];
				
				$client = new \BW\Vkontakte($vk);

				if(isset($_GET['code'])) {
					$client->authenticate();
					$_SESSION['vk']['token'] = $client->getAccessToken();
				}
				
				if($_SESSION['vk']['token']) {
					$client->setAccessToken($_SESSION['vk']['token']);
				}
				
				if($client->getAccessToken()) {
					$user_id = $client->getUserId();
					$get_user = $client->api('users.get', [
						'user_id' => $user_id,
						'fields' => ['photo_200', 'site', 'domain']
					])[0];
					if($get_user) {
						$social_pictures = [
							'avatar'		=> $get_user['photo_200'],
							'background'	=> NULL
						];
						$connect_user = [
							'id'		=> $get_user['uid'],
							'username'	=> G\sanitize_string(G\unaccent_string($get_user['first_name'] . $get_user['last_name']), true, true),
							'name'		=> trim($get_user['first_name'] . ' ' . $get_user['last_name']),
							'avatar'	=> $get_user['photo_200'],
							'url'		=> 'http://vk.com/' . $get_user['domain'],
							'website'	=> $get_user['site']
						];
						$vk_token = json_decode($client->getAccessToken());
						$connect_tokens = [
							'secret'	=> $client->getAccessToken(),
							'token_hash'=> $vk_token->access_token
						];
						$do_connect = true;
					}
				} else { 
					G\redirect($client->getLoginUrl());
				}
			break;
			
		}
		
		if($do_connect) {
			
			$login_array_db = ['type' => $doing, 'resource_id' => $connect_user['id']];
			$login = CHV\Login::get($login_array_db, ['field' => 'id', 'order' => 'asc']);
			
			// Garbage collector
			if(count($login) > 1) {
				$login_garbage = [];
				foreach($login as $k => $v) {
					$is_user = CHV\User::getSingle($v['login_user_id'], 'id', false);
					if(!$is_user) {
						CHV\Login::delete(['id' => $v['login_id']]);
					} else {
						$login = $v;
						break;
					}
				}
			} else {
				$login = $login[0];
			}
			
			// Populate the token stuff
			$login_array_db = array_merge($login_array_db, $connect_tokens);
			
			// Pupulate the rest
			$login_array_db = array_merge($login_array_db, [
				'resource_name'		=> $connect_user['name'],
				'resource_avatar'	=> $connect_user['avatar'],
				'resource_url'		=> $connect_user['url'],
				'date'				=> G\datetime(),
				'date_gmt'			=> G\datetimegmt()
			]);	
			
			// Login exists then update login
			if($login) {
				$updated_login = CHV\Login::update($login['login_id'], $login_array_db);
				// Session user doesn't match. Stop bugging
				if($_SESSION["login"] and $login['login_user_id'] !== $_SESSION["login"]['id']) {
					$logout = CHV\Login::logout($_SESSION["login"]['id']);
				}
				$user_id = $login['login_user_id'];
			} else { // Login needs to be inserted
				if(!CHV\getSetting('enable_signups') && !$_SESSION["login"]) { // Disable new sign up but allow user add connection
					G\redirect('login');
					//return $handler->issue404();
				}
				// User already logged in? (connect additional network)
				if($_SESSION["login"]) {
					$user_id = $_SESSION["login"]['id'];
					$login_array_db['user_id'] = $_SESSION["login"]['id'];
					$inserted_login = CHV\Login::insert($login_array_db);
				}
			}
			
			// Get user candidate if any
			$user = $user_id ? CHV\User::getSingle($user_id) : false;
			
			// We need to create or update the user?
			// Edit user
			if($user) {
				if(in_array($doing, ['twitter'])) {
					$user_array[$doing.'_username'] = $connect_user['username'];
				}
				if(count($user_array) > 0) {
					CHV\User::update($user_id, $user_array);
				}
			} else { // Create user (bound to social network login)
				// Wait a second, username already exists?	
				$username = '';
				preg_match_all('/[\w]/', $connect_user['username'], $user_matches);
				foreach($user_matches[0] as $match) {
					$username .= $match;
				}
				$username = substr(strtolower($username), 0, CHV\getSetting('username_max_length'));
				
				$i = 1;
				while(CHV\User::getSingle($username, 'username', false)) {
					$i++;
					$username = $connect_user['username'] . G\random_values(2, $i, 1)[0];
				}
				
				$insert_user_values = [
					'username'	=> $username,
					'name'		=> $connect_user['name'],
					'status'	=> CHV\getSetting('require_user_email_social_signup') ? 'awaiting-email' : 'valid',
					'website'	=> $connect_user['website'],
					'timezone'	=> CHV\getSetting('default_timezone'),
					'language'	=> CHV\L10n::getLocale(),
				];
				
				if(in_array($doing, ['twitter', 'facebook'])) {
					$insert_user_values[$doing.'_username'] = $connect_user['username'];
				}

				$inserted_user = CHV\User::insert($insert_user_values);
				$login_array_db['user_id'] = $inserted_user;
				$inserted_login = CHV\Login::insert($login_array_db); // Insert social network login
				
			}
			
			$user_id = $inserted_user ? $inserted_user : $user_id;
			$user = CHV\User::getSingle($user_id, 'id');
			
			// Fetch the social network images
			if(!$user or !$user['avatar']['filename'] or !$user['background']['filename']) {
				
				$avatar_needed = !$user ? true : !$user['avatar']['filename'];
				$background_needed = !$user ? true : !$user['background']['filename'];
				
				try {
					if($avatar_needed and $social_pictures['avatar']) {
						CHV\User::uploadPicture($user, 'avatar', $social_pictures['avatar']);
					}
					if($background_needed and $social_pictures['background']) {
						CHV\User::uploadPicture($user, 'background', $social_pictures['background']);
					}
				} catch(Exception $e) {
					//G\debug($e->getMessage());
				} // Silence
				
			}

			$logged_user = CHV\Login::login($user_id, $doing);
			
			$token = $connect_tokens['secret'].$connect_tokens['token_hash'];
			$hash = password_hash($token, PASSWORD_BCRYPT);
			
			$cookie = implode(':', [CHV\encodeID($user_id), $doing, $hash]) . ':' . strtotime($login_array_db['date_gmt']);
			setcookie("KEEP_LOGIN_SOCIAL", $cookie, time()+(60*60*24*30), G_ROOT_PATH_RELATIVE);
			
			$redirect_to = $_SESSION['connect_return'] ? urldecode($_SESSION['connect_return']) : $logged_user['url'];
			unset($_SESSION['connect_return']);
			
			if($_SESSION['last_url']) {
				$redirect_to = $_SESSION['last_url'];
			}
			
			G\redirect($redirect_to);
		
		}
		
		throw new Exception('Error connecting to '.$doing.'. Make sure that the credentials are ok.', 500);
	
	} catch(Exception $e) {
		G\exception_to_error($e);
	}
	
};