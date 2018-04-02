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
use G, Exception;

class Storage {

	private static $apis = [
		1 => [
			'name'	=> 'Amazon S3',
			'type'	=> 's3',
			'url'	=> 'https://s3.amazonaws.com/',
		],
		2 => [
			'name'	=> 'Google Cloud',
			'type'	=> 'gcloud',
			'url'	=> 'https://storage.googleapis.com/',
		],
		5 => [
			'name'	=> 'FTP',
			'type'	=> 'ftp',
			'url'	=> NULL,
		],
		6 => [
			'name' 	=> 'SFTP',
			'type'	=> 'sftp',
			'url'	=> NULL,
		],
		7 => [
			'name' 	=> 'OpenStack',
			'type'	=> 'openstack',
			'url'	=> NULL,
		]
	];
	
	public static function getSingle($var) {
		try {
			$storage = self::get(['id' => $var]);
			return $storage ? $storage : NULL;
		} catch(Exception $e) {
			throw new StorageException($e->getMessage(), 400);
		}
	}
	
	public static function getAll($args=[], $sort=[]) {
		try {
			$storage = self::get($args, $sort, NULL);
			return $storage ? $storage : NULL;
		} catch(Exception $e) {
			throw new StorageException($e->getMessage(), 400);
		}
	}
	
	public static function get($values=[], $sort=[], $limit=1) {
		try {
			$get = DB::get(['table' => 'storages', 'join' => 'LEFT JOIN ' . DB::getTable('storage_apis') . ' ON ' . DB::getTable('storages') . '.storage_api_id = ' . DB::getTable('storage_apis') . '.storage_api_id'], $values, 'AND', $sort, $limit);
			if($get[0]) {
				foreach($get as $k => $v) {
					self::formatRowValues($get[$k], $v);
				}
			} else {
				if($get) {
					self::formatRowValues($get);
				}
			}
			return $get;
		} catch(Exception $e) {
			throw new StorageException($e->getMessage(), 400);
		}
	}
	
	public static function uploadFiles($targets, $storage, $options=[]) {
		try {
			
			$keyprefix = $options['keyprefix'] ;
			
			if(!is_array($storage)) {
				$storage = Storage::getSingle($storage);
			} else {
				foreach(['api_id', 'key', 'secret', 'bucket'] as $k) {
					if(!isset($storage[$k])) {
						throw new Exception('Missing ' . $k . ' value in ' . __METHOD__, 100);
						break;
					}
				}
			}
			
			if(!array_key_exists('api_type', $storage)) {
				$storage['api_type'] = self::getApiType($storage['api_id']);
			}
			
			$API = self::requireAPI($storage);
			
			$files = [];
			if($targets['file']) {
				$files[] = $targets;
			} else {
				if(!is_array($targets)) {
					$files = ['file' => $targets, 'filename' => $targets];
				} else {
					$files = $targets;
				}
			}
			
			$disk_space_used = 0;
			
			// Upload the image chain
			foreach($files as $k => $v) {
				$source_file = $v['file'];
				
				switch($storage['api_type']) {
					
					case 's3':
						$source_file = @fopen($v['file'], 'r');
						if(!$source_file) {
							throw new Exception('Failed to open file stream', 100);
						}
						$API->putObject([
							'Bucket'=> $storage['bucket'],
							'Key'	=> $keyprefix . $v['filename'],
							'Body'	=> $source_file,
							'ACL'	=> 'public-read'
						]);
					break;
					
					case 'gcloud':
						// https://github.com/xown/gaufrette-gcloud/blob/master/src/Gaufrette/Adapter/GCloudStorage.php
						$source_file = @file_get_contents($v['file']);
						if(!$source_file) {
							throw new Exception('Failed to open file stream', 100);
						}
						
						// Initiate Google object storage
						$gc_obj = new \Google_Service_Storage_StorageObject();
						$gc_obj->name = $keyprefix . $v['filename'];
						$gc_obj->setAcl('public-read');
						
						// Insert the object
						$API->objects->insert($storage['bucket'], $gc_obj, [
							'mimeType'		=> G\get_mimetype($v['file']),
							'uploadType'	=> 'multipart',
							'data' 			=> $source_file,
						]);
						
						// Set this as a public object
						$gc_obj_acl = new \Google_Service_Storage_ObjectAccessControl();
						$gc_obj_acl->setEntity('allUsers');
						$gc_obj_acl->setRole('READER');
						$API->objectAccessControls->insert($storage['bucket'], $gc_obj->name, $gc_obj_acl);
					break;
					
					case 'ftp':
					case 'sftp':
						$target_path = $storage['bucket'] . $keyprefix;
						if(dirname($keyprefix . $v['filename']) !== $v['filename']) {
							$API->mkdirRecursive($keyprefix);
						}
						$API->put([
							'filename'		=> $v['filename'],
							'source_file'	=> $source_file,
							'path'			=> $target_path
						]);
						$API->chdir($storage['bucket']); // Reset pointer
					break;
					
					case 'openstack':
						$source_file = @fopen($v['file'], 'r');
						if(!$source_file) {
							throw new Exception('Failed to open file stream', 100);
						}
						$container = $API->getContainer($storage['bucket']);
						$container->uploadObject($keyprefix . $v['filename'], $source_file);
					break;
				}
				
				$filesize = @filesize($v['file']);
				if(!$filesize) {
					error_log("Can't get filesize for " . $v['file'] . " at Storage::upload");
				} else {
					$disk_space_used += $filesize;
				}
				
				$files[$k]['stored_file'] =  $storage['url'] . $keyprefix . $v['filename'];
			}
			
			// Close the FTP/SFTP once is done
			if(in_array($storage['api_type'], ['ftp', 'sftp']) and is_object($API)) {
				$API->close();
			}
			
			// Update the storage usage
			DB::increment('storages', ['space_used' => '+' . $disk_space_used], ['id' => $storage['id']]);
			
			// Update the settings table (last storage used)
			DB::update('settings', ['value' => $storage['id']], ['name' => 'last_used_storage']);
			
			return $files;
		} catch(Exception $e) {
			error_log($e);
			throw new StorageException($e->getMessage(), $e->getCode());
		}
		
	}
	
	/**
	 * Delete files from the external storage (using queues)
	 * @param $targets mixed (key, single array key, multiple array key)
	 * @param $storage mixed (storage id, storage array)
	 */
	public static function deleteFiles($targets, $storage) {
		try {
			
			if(!is_array($storage)) {
				$storage = Storage::getSingle($storage);
			} else {
				foreach(['api_id', 'key', 'secret', 'bucket'] as $k) {
					if(!isset($storage[$k])) {
						throw new Exception('Missing ' . $k . ' value in ' . __METHOD__, 100);
						break;
					}
				}
			}
			
			$files = [];
			if($targets['key']) {
				$files[] = $targets;
			} else {
				if(!is_array($targets)) {
					$files = ['key' => $targets];
				} else {
					$files = $targets;
				}
			}
			
			// Localize the array 'key'
			foreach($files as $k => $v) {
				$files[$v['key']] = $v;
				$storage_keys[] = $v['key'];
				unset($files[$k]);
			}
			
			$deleted = [];
			$disk_space_freed = 0;

			if($storage['id']) { // Storage already exist
				for($i=0; $i<count($storage_keys); $i++) {
					$queue_args = [
						'key'		=> $storage_keys[$i],
						'size'		=> $files[$storage_keys[$i]]['size']
					];
					Queue::insert(['type'=> 'storage-delete', 'args' => json_encode($queue_args), 'join' => $storage['id']]);
					$deleted[] = $v; // Just for CHV::DB, the real thing will be deleted in the queue
				}
			} else { // We are just testing the thing with a non-existent storage (DB)
			
				self::deleteObject($storage_keys[0], $storage);
				
				/*$storage_type = self::getApiType($storage['api_id']);				
				$API = self::requireAPI($storage);
				
				switch($storage_type) {
					case 's3':
						$API->deleteObject([
							'Bucket'	=> $storage['bucket'],
							'Key'		=> $storage_keys[0]
						]);
					break;
					case 'ftp':
					case 'sftp':
						$API->delete($storage_keys[0]);
					break;
				}*/

				$deleted[] = $storage_keys[0];
			}
			
			// Return the array of queued delete files (keys)
			return count($deleted) > 0 ? $deleted : FALSE;
			
		} catch(Exception $e) {
			error_log($e);
			throw new StorageException($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * Delete a single file from the external storage
	 * @param $key a representation of the object (file) to delete relative to the bucket
	 * @param $storage array with storage connection info
	 */
	public static function deleteObject($key, $storage) {
		$storage_type = self::getApiType($storage['api_id']);				
		$API = self::requireAPI($storage);
		switch(self::getApiType($storage['api_id'])) {
			case 's3':
				$API->deleteObject([
					'Bucket'	=> $storage['bucket'],
					'Key'		=> $key
				]);
			break;
			case 'gcloud':
				$API->objects->delete($storage['bucket'], $key);
			break;
			case 'ftp':
			case 'sftp':
				$API->delete($key);
			break;
			case 'openstack':
				$container = $API->getContainer($storage['bucket']);
				try {
					$object = $container->getObject($key);
				} catch(Exception $e) {} // Silence
				if($object) {
					$object->delete();
				}
			break;
		}
	}
	
	// Test the target storage with a test file upload
	public static function test($storage) {
		try {
			$datetime = preg_replace('/(.*)_(\d{2}):(\d{2}):(\d{2})/', '$1_$2h$3m$4s', G\datetimegmt('Y-m-d_h:i:s'));
			$filename = 'Chevereto_test_' . $datetime . '.png';
			$file = CHV_APP_PATH_SYSTEM . 'favicon.png';
			self::uploadFiles(['file' => $file, 'filename' => $filename], $storage);
			self::deleteFiles(['key' => $filename, 'size' => @filesize($file)], $storage);		
		} catch(Exception $e) {
			throw new StorageException($e->getMessage(), 400);
		}
	}
	
	// Insert new storage
	public static function insert($values) {
		try {
			if(!is_array($values)) {
				throw new Exception("Expecting array values, ".gettype($values)." given in " . __METHOD__, 100);
			}
			$required = ['name', 'api_id', 'key', 'secret', 'bucket', 'url']; // Global
			$required_by_api = [
				's3'  => ['region'],
				'ftp' => ['server'],
				'sftp'=> ['server'],
			];
			$storage_api = self::getApiType($values['api_id']);
			// Meet the requirements by each storage API
			if(array_key_exists('api_id', $values) && array_key_exists(self::getApiType($values['api_id']), $required_by_api)) {
				foreach($required_by_api[$storage_api] as $k => $v) {
					$required[] = $v;
				}
			}
			
			$error = FALSE;
			foreach($required as $v) {
				if(!G\check_value($values[$v])) {
					throw new Exception("Missing $v value in " . __METHOD__, 101);
				}
			}
			// Validate each value (global thing)
			$validations = [
				'api_id' => [
					'validate'	=> is_numeric($values['api_id']),
					'message'	=> "Expecting integer value for api_id, ".gettype($values['api_id'])." given in " . __METHOD__,
					'code'		=> 102
				],
				'url' => [
					'validate'	=> G\is_url($values['url']),
					'message'	=> "Invalid storage URL given in " . __METHOD__,
					'code'		=> 103
				]
			];
			// nota: add n => regions
			foreach($validations as $k => $v) {
				if(!$v['validate']) {
					throw new Exception($v['message'], $v['code']);
				}
			}
			
			// Pretty URL
			$values['url'] = G\add_ending_slash($values['url']);
			
			// Fix JSON secret key
			if($storage_api == 'gcloud') {
				$values['secret'] = trim(json_decode('{"key":"' . $values['secret'] . '"}')->key);
			}
			
			self::formatValues($values);
			
			// Test the thing
			try {
				self::test($values);
			} catch(Exception $e) {
				throw new Exception(_s("Can't verify storage credentials."), 500);
			}
			
			// OK
			return DB::insert('storages', $values);

		} catch(Exception $e) {
			throw new StorageException($e->getMessage(), $e->getCode());
		}
	}
	
	public static function update($id, $values) {
		try {
			$storage = self::getSingle($id);
			if(!$storage) {
				throw new Exception("Storage ID:$id doesn't exists", 100);
			}
			// Workaround the URL
			if(isset($values['url'])) {
				if(!G\is_url($values['url'])) {
					if(!$storage['url']) {
						throw new Exception('Missing storage URL in ' . __METHOD__, 100);
					} else {
						unset($values['url']);
					}
				} else {
					$values['url'] = G\add_ending_slash($values['url']);
				}
			} else {
				//$values['url'] = $storage['url'];
			}
			
			// Fix JSON secret key for Google Cloud
			if($values['api_id'] == 2) {
				$values['secret'] = trim(json_decode('{"key":"' . $values['secret'] . '"}')->key);
			}
			
			self::formatValues($values, 'null');
			
			// Valid capacity?
			if(array_key_exists('capacity', $values) && !empty($values['capacity']) && $values['capacity'] < $storage['space_used']) {
				throw new Exception(_s("Storage capacity can't be lower than its current usage (%s).", G\format_bytes($storage['space_used'])), 101);
			}
			
			// All the values
			$new_values = array_merge($storage, $values);
			
			// Test the credendials if needed
			$test_credentials = false;
			
			foreach(['key', 'secret', 'bucket', 'region', 'server', 'account_id', 'account_name'] as $v) {
				if(isset($values[$v]) and $values[$v] !== $storage[$v]) {
					$test_credentials = true;
					break;
				}
			}
			if($test_credentials or $values['is_active'] == 1) {
				try {
					self::test($new_values);
				} catch(Exception $e) {
					throw new Exception(_s("Can't verify storage credentials."), 500);
				}
			}
			
			/// De-activate anything else // deprecated
			/*
			if(isset($values['is_active'])) {
				$activate_this = $values['is_active'] == 1;
				if($activate_this) {
					DB::update('storages', ['is_active' => 0], ['is_active' => 1]);
				}
				DB::update('settings', ['value' => $activate_this ? $id : NULL], ['name' => 'active_storage']);
			}
			*/
			
			return DB::update('storages', $values, ['id' => $id]);
		} catch(Exception $e) {
			throw new StorageException($e->getMessage(), $e->getCode());
		}
	}
	
	// What about delete the storage and all its contents?
	/*
	public static function delete($values, $clause='AND') {
		try {
			return DB::delete('storages', $values, $clause);
		} catch(Exception $e) {
			throw new StorageException($e->getMessage(), 400);
		}
	}
	*/
	
	public static function requireAPI($storage) {
		$api_type = self::getApiType($storage['api_id']);
		
		switch($api_type) {
			
			case 's3':
				require_once(CHV_APP_PATH_LIB_VENDOR . 'amazon/aws-autoloader.php');
				return \Aws\S3\S3Client::factory([
					'key'    => $storage['key'],
					'secret' => $storage['secret'],
					'region' => $storage['region'],
					'command.params' => ['PathStyle' => true]
				]);
			break;
			
			case 'gcloud':
				require_once(CHV_APP_PATH_LIB_VENDOR . 'google/autoload.php');
				$google_client = new \Google_Client();
				$google_client->setApplicationName('Chevereto Google Cloud Storage');
				$google_service = new \Google_Service_Storage($google_client);
				
				if(isset($_SESSION['google_client_access_token'])) {
					$google_client->setAccessToken($_SESSION['google_client_access_token']);
				}
				$credentials = new \Google_Auth_AssertionCredentials(
					$storage['key'],
					['https://www.googleapis.com/auth/devstorage.full_control'],
					$storage['secret']
				);
				$google_client->setAssertionCredentials($credentials);
				if($google_client->getAuth()->isAccessTokenExpired()) {
					$google_client->getAuth()->refreshTokenWithAssertion($credentials);
				}
				$_SESSION['google_client_access_token'] = $google_client->getAccessToken();
				if(!$google_client->getAccessToken()) {
					throw new StorageException("Google cloud storage client connect error.");
				}
				return $google_service;
			break;
			
			case 'ftp':
			case 'sftp':
				$class = 'CHV\\' . ucfirst($api_type);
				return new $class([
					'server'	=> $storage['server'],
					'user'		=> $storage['key'],
					'password'	=> $storage['secret'],
					'path'		=> $storage['bucket']
				]);
			break;
			
			case 'openstack':
				require_once(CHV_APP_PATH_LIB_VENDOR . 'autoload.php');
				$credentials = [
					'username' => $storage['key'],
					'password' => $storage['secret']
				];
				foreach(['id', 'name'] as $k) {
					if(isset($storage['account_' . $k])) {
						$credentials['tenant' . ucfirst($k)] = $storage['account_' . $k];
					}
				}
				$client = new \OpenCloud\OpenStack($storage['server'], $credentials);
				return $client->objectStoreService('swift', $storage['region'], 'publicURL'); // Service
			break;
		}
	}
	
	// Get storage service regions
	public static function getAPIRegions($api) {
		$regions = [
			's3' => [
				'us-east-1' => 'US Standard',
				'us-west-2'	=> 'US West (Oregon)',
				'us-west-1' => 'US West (N. California)',
				'eu-west-1'	=> 'EU (Ireland)',
				'eu-central-1'	=> 'EU (Frankfurt)',
				'ap-southeast-1' => 'Asia Pacific (Singapore)',
				'ap-southeast-2' => 'Asia Pacific (Sydney)',
				'ap-northeast-1' => 'Asia Pacific (Tokyo)',
				'sa-east-1' => 'South America (Sao Paulo)'
			]
		];
		foreach($regions['s3'] as $k => &$v) {
			$s3_subdomain = 's3' . ($k !== 'us-east-1' ? ('-' . $k) : NULL);
			$v = [
				'name' => $v,
				'url'	=> 'https://'.$s3_subdomain.'.amazonaws.com/'
			];
		}
		return $regions[$api];
	}
	
	// Get the API type by providing the API_ID
	public static function getApiType($api_id) {
		return self::$apis[$api_id]['type'];
	}
	
	// Get a valid name to be used in the target storage
	public static function getStorageValidFilename($filename, $storage_id, $filenaming, $destination) {
		
		$extension = G\get_file_extension($filename);
		
		for($i=0; $i<25; $i++) {
			if($i==0) {
				$filenaming = $filenaming;
			} else if($i<5 && $i<15) {
				$filenaming = $filenaming == 'random' ?: 'mixed';
			} else if($i>15) {
				$filenaming = 'random';
			}
			$filename_by_method = G\get_filename_by_method($filenaming, $filename);
			$wanted_names[] = G\get_filename_without_extension($filename_by_method);
		}
		
		$stock_qry = 'SELECT DISTINCT image_name FROM ' . DB::getTable('images') . ' WHERE image_storage_id=:image_storage_id AND image_extension=:image_extension AND image_name IN('.'"'.implode('","', $wanted_names).'"'.') ';
		$stock_binds = [
			'storage_id'=> $storage_id,
			'extension'	=> $extension
		];
		
		$datefolder = preg_replace('#'.CHV_PATH_IMAGES.'#', '', $destination, 1);
		
		if($datefolder) {
			$datefolder = str_replace('/', '-', rtrim($datefolder, '/'));
			$stock_qry .= 'AND DATE(image_date_gmt)=:image_date_gmt ';
			$stock_binds['date_gmt'] = $datefolder;
		}
		$stock_qry .= 'ORDER BY image_id DESC;';
		
		try {
			$db = DB::getInstance();
			$db->query($stock_qry);
			foreach($stock_binds as $k => $v) {
				$db->bind(':image_' . $k, $v);
			}
			$images_stock = $db->fetchAll();
			$taken_names = [];
			foreach($images_stock as $k => $v) {
				$taken_names[] = $v['image_name'];
			}
		} catch(Exception $e) {}
		
		// Name taken
		if(count($taken_names) > 0) {
			foreach($wanted_names as $candidate) {
				if(in_array($candidate, $taken_names)) continue;
				$return = $candidate; break;
			}
		} else {
			$return = $wanted_names[0];
		}
		
		return $return ? ($return . '.' . $extension) : self::getStorageValidFilename($filename, $storage_id, $filenaming);

	}
	
	public static function getApis() {
		return self::$apis;
	}
	
	// Always match the right thing
	private static function formatValues(&$values, $junk='keep') {
		
		// Capacity as bytes
		if(array_key_exists('capacity', $values)) {
			G\nullify_string($values['capacity']);
			if(!is_null($values['capacity'])) {
				$values['capacity'] = G\get_bytes($values['capacity']);
				if(!is_numeric($values['capacity'])) { // G\get_bytes returns FLOAT
					throw new StorageException('Invalid storage capacity value. Make sure to use a valid format.', 100);
				}
			}
		}
		
		// Workaround the https thing
		if(array_key_exists('is_https', $values)) {
			if(!$values['url']) {
				$values['url'] = $storage['url'];
			}
			$protocol_stock = ['http','https'];
			if($values['is_https'] != 1) {
				$protocol_stock = array_reverse($protocol_stock);
			}
			$values['url'] = preg_replace('#^https?://#', '', $values['url'], 1); // Remove protocol
			$values['url'] = $protocol_stock[1] . '://' . $values['url'];
		} elseif(array_key_exists('url', $values)) {
			$values['is_https'] = (int)G\is_https($values['url']);
		}
		
		// Get rid of some junk
		if(in_array($junk, ['null', 'remove']) and array_key_exists('api_id', $values)) {
			$junk_values_by_api = [
				1 => ['server'],
				5 => ['region']
			];
			if(array_key_exists('api_id', $junk_values_by_api)) {
				switch($junk) {
					case 'null':
						foreach($junk_values_by_api[$values['api_id']] as $k => $v) {
							$values[$v] = NULL;
						}
					break;
					case 'remove':
						$values = G\array_filter_array($values, $junk_values_by_api[$values['api_id']], 'rest'); 
					break;
				}
			}
		}
	}
	
	// Format get row return
	private static function formatRowValues(&$values, $row=[]) {
		$values = DB::formatRow(count($row) > 0 ? $row : $values);
		$values['url'] = G\is_url($values['url']) ? G\add_ending_slash($values['url']) : NULL;
		$values['usage_label'] = ($values['capacity'] == 0 ? _s('Unlimited') : G\format_bytes($values['capacity'], 2)) . ' / ' . G\format_bytes($values['space_used'], 2) . ' ' .  _s('used');
	}
}

class StorageException extends Exception {}