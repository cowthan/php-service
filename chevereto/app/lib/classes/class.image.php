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

class Image {
	
	static $table_chv_image = [
				'name',
				'extension',
				'album_id',
				'size',
				'width',
				'height',
				'date',
				'date_gmt',
				'nsfw',
				'user_id',
				'uploader_ip',
				'storage_mode',
				'storage_id',
				'md5',
				'original_filename',
				'original_exifdata',
				'category_id',
				'description',
				'chain',
				'thumb_size',
				'medium_size',
				'title'
			];
	static $chain_sizes = ['original', 'image', 'medium', 'thumb'];
	
	public static function getSingle($id, $sumview=false, $pretty=false) {
		
		$tables = DB::getTables();
		$query = 'SELECT * FROM '.$tables['images']."\n";
		
		$joins = [
			'LEFT JOIN '.$tables['storages'].' ON '.$tables['images'].'.image_storage_id = '.$tables['storages'].'.storage_id',
			'LEFT JOIN '.$tables['storage_apis'].' ON '.$tables['storages'].'.storage_api_id = '.$tables['storage_apis'].'.storage_api_id',
			'LEFT JOIN '.$tables['users'].' ON '.$tables['images'].'.image_user_id = '.$tables['users'].'.user_id',
			'LEFT JOIN '.$tables['albums'].' ON '.$tables['images'].'.image_album_id = '.$tables['albums'].'.album_id'
		];

		$query .=  implode("\n", $joins) . "\n";
		$query .= 'WHERE image_id=:image_id;'."\n";
		
		if($sumview) {
			$query .= 'UPDATE '.$tables['images'].' SET image_views = image_views + 1 WHERE image_id=:image_id';
		}
		
		try {
			$db = DB::getInstance();
			$db->query($query);
			$db->bind(':image_id', $id);
			$image_db = $db->fetchSingle();
			if($image_db) {
				if($sumview) {
					$image_db['image_views'] = $image_db['image_views'] + 1;
				}
				
				$return = $image_db;
				$return = $pretty ? self::formatArray($return) : $return;
				
				if(!$return['file_resource']) {
					$return['file_resource'] =  self::getSrcTargetSingle($image_db, true);
				}
				return $return;
			} else {
				return $image_db;
			}
		} catch(Exception $e) {
			throw new ImageException($e->getMessage(), 400);
		}
		
	}
	
	public static function getMultiple($ids, $pretty=false) {
		
		if(!is_array($ids)) {
			$ids = func_get_args();
			$aux = array();
			foreach($ids as $k => $v) {
				$aux[] = $v;
			}
			$ids = $aux;
		}
		
		if(count($ids) == 0) {
			throw new ImageException('Null ids provided in Image::get_multiple', 100);
		}
		
		$tables = DB::getTables();
		$query = 'SELECT * FROM '.$tables['images']."\n";
		
		$joins = array(
			'LEFT JOIN '.$tables['users'].' ON '.$tables['images'].'.image_user_id = '.$tables['users'].'.user_id',
			'LEFT JOIN '.$tables['albums'].' ON '.$tables['images'].'.image_album_id = '.$tables['albums'].'.album_id'
		);

		$query .=  implode("\n", $joins) . "\n";
		$query .= 'WHERE image_id IN ('. join(',', $ids). ')' . "\n";
		
		try {
			$db = DB::getInstance();
			$db->query($query);
			$images_db = $db->fetchAll();
			if($images_db) {
				foreach($images_db as $k => $v) {
					$images_db[$k] = array_merge($v, self::getSrcTargetSingle($v, true)); // todo
				}
			}
			if($pretty) {
				$return = [];
				foreach($images_db as $k => $v) {
					$return[] = self::formatArray($v);
				}
				return $return;
			}
			return $images_db;
		} catch(Exception $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
	public static function getAlbumSlice($image_id, $album_id=NULL, $padding=2) {
		
		$tables = DB::getTables();
		
		if($image_id == NULL) {
			throw new ImageException("Image id can't be NULL", 100);
		}
		
		if($album_id == NULL) {
			try {
				$db = DB::getInstance();
				$db->query('SELECT image_album_id FROM '.$tables['images'].' WHERE image_id=:image_id');
				$db->bind(':image_id', $image_id);
				$image_album_db = $db->fetchSingle();
				$album_id = $image_album_db['image_album_id'];
			} catch(Excepton $e) {
				throw new ImageException($e->getMessage(), 400);
			}
			
			if($album_id == NULL) {
				return;
			}
		}
		
		if(!is_numeric($padding)) {
			$padding = 2;
		}

		//$where_album = $album_id !== NULL ? "image_album_id=:image_album_id" : "image_album_id IS NULL";
		
		try {
			$db = DB::getInstance();
			$db->query('SELECT * FROM (
                    (SELECT * FROM '.$tables['images'].' LEFT JOIN '.$tables['storages'].' ON '.$tables['images'].'.image_storage_id = '.$tables['storages'].'.storage_id
                    WHERE image_album_id=:image_album_id AND image_id <= :image_id ORDER BY image_id DESC LIMIT 0,'.($padding*2 + 1).')
                    UNION
                    (SELECT * FROM '.$tables['images'].' LEFT JOIN '.$tables['storages'].' ON '.$tables['images'].'.image_storage_id = '.$tables['storages'].'.storage_id
                    WHERE image_album_id=:image_album_id AND image_id > :image_id ORDER BY image_id ASC LIMIT 0,'.($padding*2).')
                ) images ORDER BY images.image_id ASC');

			$db->bind(':image_album_id', $album_id);
			$db->bind(':image_id', $image_id);
			
			$image_album_slice_db = $db->fetchAll();
			
			$album_offset = array('top' => 0, 'bottom' => 0);
			foreach($image_album_slice_db as $v) {
				if($image_id > $v['image_id']) {
					$album_offset['top']++;
				}
				if($image_id < $v['image_id']) {
					$album_offset['bottom']++;
				}
			}
			
			$album_chop_count = count($image_album_slice_db);
			$album_iteration_times = $album_chop_count - ($padding*2 + 1);
			
			if($album_chop_count > ($padding*2 + 1)) {
									
				if($album_offset['top'] > $padding && $album_offset['bottom'] > $padding) {
					// Cut on top
					for($i=0; $i<$album_offset['top']-$padding; $i++) {
						unset($image_album_slice_db[$i]);
					}
					// Cut on bottom
					for($i=1; $i<=$album_offset['bottom']-$padding; $i++) {
						unset($image_album_slice_db[$album_chop_count - $i]);
					}
				} else if($album_offset['top'] <= $padding) {
					// Cut bottom
					for($i=0; $i<$album_iteration_times; $i++) {
						unset($image_album_slice_db[$album_chop_count - 1 - $i]);
					}
				} else if($album_offset['bottom'] <= $padding) {
					// Cut top
					for($i=0; $i<$album_iteration_times; $i++) {
						unset($image_album_slice_db[$i]);
					}
				}
				
				// Some cleaning after the unsets
				$image_album_slice_db = array_values($image_album_slice_db);
			}

			$album_cursor = '';
			foreach($image_album_slice_db as $k => $v) {
				if($v['image_id'] == $image_id) {
					$album_cursor = $k;
					break;
				}
			}
			
			$image_album_slice['images'] = array();
			
			foreach($image_album_slice_db as $k => $v) {
				$image_album_slice['images'][$k] = self::formatArray($v);
			}
			
			if($image_album_slice['images'][$album_cursor-1]) {
				$image_album_slice['prev'] =  $image_album_slice['images'][$album_cursor-1];
			}
			
			if($image_album_slice['images'][$album_cursor+1]) {
				$image_album_slice['next'] = $image_album_slice['images'][$album_cursor+1];
			}
			
			return array(
				'db'		=> $image_album_slice_db,
				'formatted'	=> $image_album_slice
			);

		} catch(Exception $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
	public static function getSrcTargetSingle($filearray, $prefix=true) {
		
		$prefix = $prefix ? 'image_' : NULL;
		$folder = CHV_PATH_IMAGES;
		$pretty = !isset($filearray['image_id']);
		
		$chain_mask = str_split((string) str_pad(decbin($filearray[$pretty ? 'chain' : 'image_chain']), 4, '0', STR_PAD_LEFT));
		
		$chain_to_sufix = [
			//'original'	=> '.original.',
			'image'		=> '.',
			'thumb'		=> '.th.',
			'medium'	=> '.md.'
		];

		if($pretty) {
			$type = $filearray['storage']['id'] ? 'url' : 'path';
		} else {
			$type = $filearray['storage_id'] ? 'url' : 'path';
		}
		
		if($type == 'url') { // URL resource folder
			$folder = G\add_ending_slash($pretty ? $filearray['storage']['url'] : $filearray['storage_url']);
		}
		
		switch($filearray[$prefix.'storage_mode']) {
			case 'datefolder':
				$datetime = $filearray[$prefix.'date'];
				$datefolder = preg_replace('/(.*)(\s.*)/', '$1', str_replace('-', '/', $datetime));
				$folder .= G\add_ending_slash($datefolder); // Y/m/d/
			break;
			case 'old':
				$folder .= 'old/';
			break;
			case 'direct':
				// use direct $folder
			break;
		}
		
		$targets = [
			'type' => $type,
			'chain'	=> [
				//'original'	=> NULL,
				'image'		=> NULL,
				'thumb'		=> NULL,
				'medium'	=> NULL
			]
		];
		
		foreach($targets['chain'] as $k => $v) {
			$targets['chain'][$k] = $folder.$filearray[$prefix.'name'] . $chain_to_sufix[$k] . $filearray[$prefix.'extension'];
		}
		
		if($type == 'path') {
			foreach($targets['chain'] as $k => $v) {
				if(!file_exists($v)) {
					unset($targets['chain'][$k]);
				};
			}
		} else {
			foreach($chain_mask as $k => $v) {
				if(!(bool) $v) {
					unset($targets['chain'][self::$chain_sizes[$k]]);
				}
			}
		}
		
		return $targets;
	}
	
	public static function getUrlViewer($id_encoded) {
		return G\get_base_url('image/'.$id_encoded);
	}
	
	public static function watermark($image_path, $options=[]) {
		
		// Watermark options
		$options = array_merge([
			'ratio'		=> getSetting('watermark_percentage') / 100,
			'position'	=> explode(' ', getSetting('watermark_position')),
			'file'		=> CHV_PATH_CONTENT_IMAGES_SYSTEM . getSetting('watermark_image')
		], $options);
		
		if(!is_readable($options['file'])) {
			throw new Exception("Can't read watermark file", 100);
		}
		
		// Fail-safe ratio
		$options['ratio'] = min(1, (!is_numeric($options['ratio']) ? 0.01 : max(0.01, $options['ratio'])));
		
		// Fail-safe positioning
		if(!in_array($options['position'][0], ['left', 'center', 'right'])) {
			$options['position'][0] = 'right';
		}
		if(!in_array($options['position'][1], ['top', 'center', 'bottom'])) {
			$options['position'][0] = 'bottom';
		}
		
		// Get source fileinfo
		$image_fileinfo = G\get_image_fileinfo($image_path);
		
		// Create working source image
		switch($image_fileinfo['extension']) {
			case 'gif':
				$src = imagecreatefromgif($image_path);
			break;
			case 'png':
				$src = imagecreatefrompng($image_path);
			break;
			case 'jpg':
				$src = imagecreatefromjpeg($image_path);
			break;
		}
		$src_width = imagesx($src);
		$src_height = imagesy($src);
		
		// Create working watermark image
		$watermark_fileinfo = G\get_image_fileinfo($options['file']);
		
		$watermark_width = $watermark_fileinfo['width'];
		$watermark_height = $watermark_fileinfo['height'];
		$watermark_max_upscale = 1.2;
		$watermark_max_width = $watermark_fileinfo['width'] * $watermark_max_upscale;
		$watermark_max_height = $watermark_fileinfo['height'] * $watermark_max_upscale;
		
		$watermark_area = $image_fileinfo['width'] * $image_fileinfo['height'] * $options['ratio'];
		$watermark_image_ratio = $watermark_fileinfo['ratio'];
		
		$watermark_new_height = round(sqrt($watermark_area/$watermark_image_ratio), 0);
		
		if($watermark_new_height > $watermark_max_height) { // Set a max cap
			$watermark_new_height = $watermark_max_height;
		}
		
		// To legit to quit
		if($watermark_new_height > $src_height) {
			$watermark_new_height = $src_height;
		}
		
		// Fix watermark margin issues on height
		if(getSetting('watermark_margin') and $options['position'][1] !== 'center' and $watermark_new_height + getSetting('watermark_margin') > $src_height) {
			$watermark_new_height -= $watermark_new_height + 2*getSetting('watermark_margin') - $src_height;
		}
		
		$watermark_new_width  = round($watermark_image_ratio * $watermark_new_height, 0);
		
		// To legit to quit yo
		if($watermark_new_width > $src_width) {
			$watermark_new_width = $src_width;
		}
		
		// Fix watermark margin issues on width
		if(getSetting('watermark_margin') and $options['position'][0] !== 'center' and $watermark_new_width + getSetting('watermark_margin') > $src_width) {
			$watermark_new_width -= $watermark_new_width + 2*getSetting('watermark_margin') - $src_width;
			$watermark_new_height = $watermark_new_width / $watermark_image_ratio;
		}
		
		if($watermark_new_height <= $watermark_max_height or $watermark_new_width <= $watermark_fileinfo['width']*2) {
			// Resizable watermark image
			try {
				$watermark_tempnam = @tempnam(sys_get_temp_dir(), 'chvtemp');
				self::resize($options['file'], dirname($watermark_tempnam), basename($watermark_tempnam), ['width' => $watermark_new_width]);
				$watermark_temp = $watermark_tempnam . '.png';
				$watermark_src = imagecreatefrompng($watermark_temp);
				$watermark_width = imagesx($watermark_src);
				$watermark_height = imagesy($watermark_src);
				@unlink($watermark_tempnam);
			} catch(Exception $e) {
				error_log($e);
			} // Silence
		} else {
			// Watermark "as is"
			$watermark_src = imagecreatefrompng($options['file']);
		}
		

		// Calculate the watermark position
		switch($options['position'][0]) {
			case 'left':
				$watermark_x = getSetting('watermark_margin');
			break;
			case 'center':
				$watermark_x = $src_width/2 - $watermark_width/2;
			break;
			case 'right':
				$watermark_x = $src_width - $watermark_width - getSetting('watermark_margin');
			break;
		}
		switch($options['position'][1]) {
			case 'top':
				$watermark_y = getSetting('watermark_margin');
			break;
			case 'center':
				$watermark_y = $src_height/2 - $watermark_height/2;
			break;
			case 'bottom':
				$watermark_y = $src_height - $watermark_height - getSetting('watermark_margin');
			break;
		}
		
		/*
		// Watermark has the same or greater size of the image ?
		// --> Center the watermark
		if($watermark_width == $src_width && $watermark_height == $src_height) {
			$watermark_x = $src_width/2 - $watermark_width/2;
			$watermark_y = $src_height/2 - $watermark_height/2;
		}
		
		// Watermark is too big ?
		// --> Fit the watermark on the image
		if($watermark_width > $src_width || $watermark_height > $src_height) {
			// Watermark is wider than the image
			if($watermark_width > $src_width) {
				$watermark_new_width  = $src_width;
				$watermark_new_height = $src_width * $watermark_height / $watermark_width;
				if($watermark_new_height > $src_height) {
					$watermark_new_width  = $src_height * $watermark_width / $watermark_height;
					$watermark_new_height = $src_height;
				}
			} else {
				$watermark_new_width  = $src_height * $watermark_width / $watermark_height;
				$watermark_new_height = $src_height;
			}
			try {
				$watermark_temp = @tempnam(sys_get_temp_dir(), 'chvtemp');
				self::resize($options['file'], $watermark_temp, ['width' => $watermark_new_width]);
				$watermark_width = $watermark_new_width;
				$watermark_height = $watermark_new_height;
				$watermark_src = imagecreatefrompng($watermark_temp);
				$watermark_x = $src_width/2 - $watermark_width/2;
				$watermark_y = $src_height/2 - $watermark_height/2;
			} catch(Exception $e) {} // Silence
		}
		*/
		
		// Apply and save the watermark
		G\imagecopymerge_alpha($src, $watermark_src, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height, getSetting('watermark_opacity'), $image_fileinfo['extension']);
		
		switch($image_fileinfo['extension']) {
			case 'gif': // Cast gif as png (gif has very poor quality)
				imagegif($src, $image_path);
			case 'png':
				imagepng($src, $image_path);
			break;
			case 'jpg':
				imagejpeg($src, $image_path, 90);
			break;
		}
		imagedestroy($src);
		@unlink($watermark_temp);
		
		return true;
				
	}
	
	public static function upload($source, $destination, $filename=NULL, $options=[], $storage_id=NULL) {
		
		$default_options = array(
			'max_size'		=> G\get_bytes('2 MB'),
			'filenaming'	=> 'original'
		);
		
		$options = array_merge($default_options, $options);

		if(!is_null($filename) and !$options['filenaming']) {
			$options['filenaming'] = 'original';
		}
		
		try {
			$upload = new Upload;
			$upload->setSource($source);
			$upload->setDestination($destination);
			$upload->setOptions($options);
			if(!is_null($storage_id)) {
				$upload->setStorageId($storage_id);
			}
			if(!is_null($filename)) {
				$upload->setFilename($filename);
			}
			$upload->exec();
			
			$is_animated_image = ($upload->uploaded['fileinfo']['extension'] == 'gif' and G\is_animated_image($upload->uploaded['file']));
			$apply_watermark = ($options['watermark'] and !$is_animated_image);
			
			// Disable animated image watermark
			if($is_animated_image) {
				$apply_watermark = FALSE;
			}

			if($apply_watermark) {
				// Detect watermark min image requirement
				foreach(['width', 'height'] as $k) {
					$min_value = getSetting('watermark_target_min_' . $k);
					if($min_value == 0) { // Skip on zero
						continue;
					}
					$apply_watermark = $upload->uploaded['fileinfo'][$k] >= $min_value;
				}
				// Disable on GIF image?
				if($apply_watermark and $upload->uploaded['fileinfo']['extension'] == 'gif' and !$options['watermark_gif']) {
					$apply_watermark = FALSE;
				}
			}

			if($apply_watermark and self::watermark($upload->uploaded['file'])) {
				$upload->uploaded['fileinfo'] = get_image_fileinfo($upload->uploaded['file']); 	// Remake the fileinfo array
			}
			
			return [
				'uploaded'	=> $upload->uploaded,
				'source'	=> $upload->source
			];
		} catch(Exception $e) {
			throw new UploadException($e->getMessage(), $e->getCode());
		}
		
	}
	
	// Mostly for people uploading two times the same image to test or just bug you
	public static function isDuplicatedUpload($md5_file, $time_frame='P1D') {
		$db = DB::getInstance();
		$db->query('SELECT * FROM ' . DB::getTable('images') . ' WHERE image_md5=:md5 AND image_uploader_ip=:ip AND image_date_gmt > :date_gmt');
		$db->bind(':md5', $md5_file);
		$db->bind(':ip', G\get_client_ip());
		$db->bind(':date_gmt', G\datetime_sub(G\datetimegmt(), $time_frame));
		$db->exec();
		return $db->fetchColumn();
	}
	
	public static function uploadToWebsite($source, $user, $params=[]) {
		 
		try {
			
			// Get user
			if($user) {
				switch(gettype($user)) {
					case 'string':
						$user = User::getSingle($user, 'username');
					break;
					case 'integer':
						$user = User::getSingle($user);
					break;
				}
			}
			
			// Detect duplicated uploads (local) by IP + MD5 (first layer)
			if(is_array($source) and array_key_exists('tmp_name', $source) and !$user['is_admin']) {
				$md5_file = md5_file($source['tmp_name']);
				if($md5_file and self::isDuplicatedUpload($md5_file)) {
					throw new Exception(_s('Duplicated upload'), 100);
				}
			}

			$get_active_storages = Storage::getAll(['is_active' => 1]); // Get all active storages
			
			if($get_active_storages) {
				// Sequential storage thing
				$sequential_storage = count($get_active_storages) > 1;
				if($sequential_storage) {
					$storage_id = NULL;
					$last_used_storage = getSetting('last_used_storage');
				} else {
					$storage_id = $get_active_storages[0]['id'];
				}
				
				$active_storages = [];
				for($i=0; $i<count($get_active_storages); $i++) {
					$storage_pointer = $get_active_storages[$i]['id']; // id
					$active_storages[$storage_pointer] = $get_active_storages[$i]; // key fixed array
					
					if(is_null($storage_id) && $last_used_storage == $storage_pointer) {
						$storage_id = $get_active_storages[$i+1]['id'] ?: $get_active_storages[0]['id'];
					}
				}

				// Set the storage array
				$storage = $active_storages[$storage_id];
				
			}
			
			$storage_mode = getSetting('upload_storage_mode');
			$datefolder = date('Y/m/d/');
			switch($storage_mode) {
				case 'direct':
					$upload_path = CHV_PATH_IMAGES;
				break;
				case 'datefolder':
					$upload_path = CHV_PATH_IMAGES . $datefolder;
				break;
			}
			
			$upload_options = [
				'max_size'		=> G\get_bytes(getSetting('upload_max_filesize_mb') . ' MB'),
				'filenaming'	=> in_array($params['privacy'], ['private', 'private_but_link']) ? 'random' : getSetting('upload_filenaming'),
				'watermark'		=> getSetting('watermark_enable'),
			];
			
			// Workaround watermark by user group
			if($upload_options['watermark']) {
				$watermark_enable = [];
				$watermark_user = $user ? ($user['is_admin'] ? 'admin' : 'user') : 'guest';
				$upload_options['watermark'] = getSetting('watermark_enable_' . $watermark_user);
			}
			
			// Watermark by filetype
			$upload_options['watermark_gif'] = (bool) getSetting('watermark_enable_file_gif');
			
			$image_upload = self::upload($source, $upload_path, NULL, $upload_options, $storage_id);
			$chain_mask = [0, 1, 0, 1]; // original image medium thumb
			$chain_array = [];
			
			// Detect duplicated uploads (all) by IP + MD5 (second layer)
			if(!is_array($source) and $image_upload['uploaded']['fileinfo']['md5'] and !$user['is_admin']) {
				if(self::isDuplicatedUpload($image_upload['uploaded']['fileinfo']['md5'])) {
					throw new Exception(_s('Duplicated upload'), 100);
				}
			}
			
			// Handle resizing KEEP 'source', change 'uploaded'
			$must_resize = FALSE;
			foreach(['width', 'height'] as $k) {
				if(!isset($params[$k]) or !is_numeric($params[$k])) continue;
				if($params[$k] != $image_upload['uploaded']['fileinfo'][$k]) {
					$must_resize = TRUE;
				}
			}
			
			// Disable resize for animated images (for now)
			if(G\is_animated_image($image_upload['uploaded']['file'])) {
				$must_resize = FALSE;
			}
			
			if($must_resize) {
				$image_ratio = $image_upload['uploaded']['fileinfo']['width'] / $image_upload['uploaded']['fileinfo']['height'];
				if($image_ratio == $params['width']/$params['height']) {
					$image_resize_options = [
						'width'		=> $params['width'],
						'height'	=> $params['height']
					];
				} else {
					$image_resize_options = ['width' => $params['width']];
				}
				$image_upload['uploaded'] = self::resize($image_upload['uploaded']['file'], dirname($image_upload['uploaded']['file']), NULL, $image_resize_options);
			} 
			
			// Try to generate the thumb
			$image_thumb_options = [
				'width'		=> getSetting('upload_thumb_width'),
				'height'	=> getSetting('upload_thumb_height')
			];
			
			// Try to generate the medium
			$medium_size = getSetting('upload_medium_width');

			$is_animated_image = $image_upload['uploaded']['fileinfo']['extension'] == 'gif' and G\is_animated_image($image_upload['uploaded']['file']);
			
			// Medium sized image
			if($image_upload['uploaded']['fileinfo']['width'] > $medium_size or $is_animated_image) {
				$image_medium_options = [];
				$image_medium_options['width'] = $medium_size;
	
				if($is_animated_image) {
					$image_medium_options['forced'] = true;
					$image_medium_options['width'] = min($image_medium_options['width'], $image_upload['uploaded']['fileinfo']['width']);
				}
				
				$image_medium = self::resize($image_upload['uploaded']['file'], dirname($image_upload['uploaded']['file']), $image_upload['uploaded']['name'] . '.md', $image_medium_options);
				$chain_mask[2] = 1;
			}
			
			// Thumb sized image
			$image_thumb = self::resize($image_upload['uploaded']['file'], dirname($image_upload['uploaded']['file']), $image_upload['uploaded']['name'] . '.th', $image_thumb_options);
			
			// Image chain (binary)
			$chain_value = bindec((int)implode('', $chain_mask));
			
			$disk_space_needed = $image_upload['uploaded']['fileinfo']['size'] + $image_thumb['fileinfo']['size'] + ($image_medium['fileinfo']['size'] ?: 0);
			
			// Can the storage allocate all the files?
			if($storage_id and !empty($storage['capacity']) and $disk_space_needed > ($storage['capacity'] - $storage['space_used'])) {

				if(count($active_storages) > 0) { // Moar
					$capable_storages = [];
					foreach($active_storages as $k => $v) {
						if($v['id'] == $storage_id or $disk_space_needed > ($v['capacity'] - $v['space_used'])) continue;
						$capable_storages[] = $v['id'];
					}
					if(count($capable_storages) == 0) {
						$switch_to_local = true;
					} else {
						$storage_id = $capable_storages[0];
						$storage = $active_storages[$storage_id];
					}
				} else {
					$switch_to_local = true;
				}
				
				// Switch to local storage
				if($switch_to_local) {
				
					$storage_id = NULL;
					$downstream = $image_upload['uploaded']['file'];
					$fixed_filename = $image_upload['uploaded']['filename'];
					$uploaded_file = G\name_unique_file($upload_path, $upload_options['filenaming'], $fixed_filename);
					if(!@rename($downstream, $uploaded_file)) {
						throw new Exception("Can't re-allocate image to local storage", 500);
					}
					
					// Re-build the uploaded array
					$image_upload['uploaded'] = [
						'file'		=> $uploaded_file,
						'filename'	=> G\get_filename($uploaded_file),
						'name'		=> G\get_filename_without_extension($uploaded_file),
						'fileinfo'	=> G\get_image_fileinfo($uploaded_file)
					];
					
					// ...And re-build all the chain
					$chain_props = [
						'thumb'		=> ['suffix' => 'th'],
						'medium'	=> ['suffix' => 'md']
					];
					if(!$image_medium) unset($chain_props['medium']);
					
					foreach($chain_props as $k => $v) {
						$chain_file = G\add_ending_slash(dirname($image_upload['uploaded']['file'])) . $image_upload['uploaded']['name'] . '.'.$v['suffix'].'.' . ${"image_$k"}['fileinfo']['extension'];
						if(!@rename(${"image_$k"}['file'], $chain_file)) {
							throw new Exception("Can't re-allocate image ".$k." to local storage", 500);
						}
						${"image_$k"} = [
							'file'		=> $chain_file,
							'filename'	=> G\get_filename($chain_file),
							'name'		=> G\get_filename_without_extension($chain_file),
							'fileinfo'	=> G\get_image_fileinfo($chain_file)
						];
					}
				
				}
			}
			
			$image_insert_values = [
				'storage_mode'	=> $storage_mode,
				'storage_id'	=> $storage_id,
				'user_id'		=> $user['id'],
				'album_id'		=> $params['album_id'],
				'nsfw'			=> $params['nsfw'],
				'category_id'	=> $params['category_id'],
				'title'			=> $params['title'],
				'description'	=> $params['description'],
				'chain'			=> $chain_value,
				'thumb_size'	=> $image_thumb['fileinfo']['size'],
				'medium_size'	=> $image_medium['fileinfo']['size'] ?: 0			
			];
			
			if($storage_id) {
				foreach(self::$chain_sizes as $k => $v) {
					if(!(bool)$chain_mask[$k]) continue;
					switch($v) {
						case 'image':
							$prop = $image_upload['uploaded'];
						break;
						default:
							$prop = ${"image_$v"};
						break;
					}
					$toStorage[$v] = ['file' => $prop['file'], 'filename' => $prop['filename']];
				}
				try {
					Storage::uploadFiles($toStorage, $storage, ['keyprefix' => $storage_mode == 'datefolder' ? $datefolder : NULL]);
				} catch(Exception $e) {
					throw new Exception("Can't upload image - Storage error: " .$e->getMessage(), $e->getCode());
				}
			}
			
			if(!$params['title']) {
				$title_from_filename = mb_substr(preg_replace('/[-_\s]+/', ' ', trim($image_upload['source']['name'])), 0, 100, 'UTF-8');
				$image_insert_values['title'] = ucfirst($title_from_filename);
			}
			
			$uploaded_id = self::insert($image_upload, $image_insert_values);
			
			if($toStorage) {
				foreach($toStorage as $k => $v) {
					@unlink($v['file']); // Remove the source image
				}
			}
			
			// Private upload? Create a private album then
			if(in_array($params['privacy'], ['private', 'private_but_link'])) {
				$upload_timestamp = $params['timestamp'];	
				if(!$_SESSION['upload_'.$upload_timestamp]) {
					$inserted_album = Album::insert(_s('Private upload').' '.G\datetime('Y-m-d'), $user['id'], $params['privacy']);
					$_SESSION['upload_'.$upload_timestamp] = encodeID($inserted_album);
				}
				Album::addImage(decodeID($_SESSION['upload_'.$upload_timestamp]), $uploaded_id);
			}
			
			// Update user (if any)
			if($user) {
				DB::increment('users', ['image_count' => '+1'], ['id' => $user['id']]);
			}
			
			return $uploaded_id;
			
		} catch(Exception $e) {
			@unlink($image_upload['uploaded']['file']);
			@unlink($image_medium['file']);
			@unlink($image_thumb['file']);
			throw $e;
		}
		
	}
	
	public static function resize($source, $destination, $filename=NULL, $options=[]) {
		try {
			$resize = new Imageresize;
			$resize->setSource($source);
			$resize->setDestination($destination);
			if($filename) {
				$resize->setFilename($filename);
			}
			$resize->setOptions($options);
			if($options['width']) {
				$resize->set_width($options['width']);
			}
			if($options['height']) {
				$resize->set_height($options['height']);
			}
			if($options['width'] == $options['height']) {
				$resize->set_fixed();
			}
			if($options['forced']) {
				$resize->setOption('forced', true);
			}
			$resize->exec();
			return $resize->resized;
		} catch(Exception $e) {
			throw new ImageException($e->getMessage(), $e->getCode());
		}
	}
	
	public static function insert($image_upload, $values=[]) {
		try {
			$table_chv_image = self::$table_chv_image;
			foreach($table_chv_image as $k => $v) {
				$table_chv_image[$k] = 'image_' . $v;
			}
			
			// Remove eternal/useless MakerNote
			if($image_upload['source']['image_exif']['MakerNote']) {
				unset($image_upload['source']['image_exif']['MakerNote']);
			}
			
			$original_exifdata = $image_upload['source']['image_exif'] ? json_encode(G\array_utf8encode($image_upload['source']['image_exif'])) : NULL;
			
			// Fix some values
			$values['nsfw'] = in_array(strval($values['nsfw']), ['0','1']) ? $values['nsfw'] : 0;
			
			$populate_values = [
				'date'				=> G\datetime(),
				'date_gmt'			=> G\datetimegmt(),
				'uploader_ip'		=> G\get_client_ip(),
				'md5'				=> md5_file($image_upload['uploaded']['file']),
				'original_filename' => $image_upload['source']['filename'],
				'original_exifdata' => $original_exifdata
			];
			
			// Populate values with fileinfo + populate_values
			$values = array_merge($image_upload['uploaded']['fileinfo'], $populate_values, $values);

			foreach(['title', 'description', 'category_id'] as $v) {
				G\nullify_string($values[$v]);
			}
			
			// Now use only the values accepted by the table
			foreach($values as $k => $v) {
				if(!in_array('image_' . $k, $table_chv_image)) {
					unset($values[$k]);
				}
			}
			
			$insert = DB::insert('images', $values);
			
			if(!is_null($values['album_id']) and $insert) {
				Album::updateImageCount($values['album_id'], 1);
			}
			
			return $insert;

		} catch(Exception $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
	public static function update($id, $values) {
		try {
			
			$values = G\array_filter_array($values, self::$table_chv_image, 'exclusion');
			
			foreach(['title', 'description', 'category_id'] as $v) {
				if(!array_key_exists($v, $values)) continue;
				G\nullify_string($values[$v]);
			}
			
			if(isset($values['album_id'])) {
				$image_db = self::getSingle($id, false, false);
				$old_album = $image_db['image_album_id'];
				$new_album = $values['album_id'];
				$update = DB::update('images', $values, ['id' => $id]);
				if($update and $old_album !== $new_album) {
					if(!is_null($old_album)) { // Update the old album
						Album::updateImageCount($old_album, 1, '-');
					}
					if(!is_null($new_album)) { // Update the new album
						Album::updateImageCount($new_album, 1);
					}
				}
				return $update;
			} else {
				return DB::update('images', $values, ['id' => $id]);
			}
		} catch(Excepton $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
	public static function delete($id, $update_user=true) {
		try {
			if($update_user) {
				// Get the user id
				$user_id = DB::get('images', ['id' => $id])[0]['image_user_id'];
			}
			
			$image = self::getSingle($id, false, true);
			
			if($image['file_resource']['type'] == 'path') {			
				foreach($image['file_resource']['chain'] as $file_delete) {
					if(file_exists($file_delete) and !@unlink($file_delete)) {
						throw new ImageException("Can't delete file", 200);
					}
				}
			} else {
				
				$targets = [];
				$disk_space_used = $image['size'] + $image['thumb']['size'] + $image['medium']['size'];
				
				foreach($image['file_resource']['chain'] as $k => $v) {
					$targets[$k] = [
						'key' => preg_replace('#'.G\add_ending_slash($image['storage']['url']).'#', '', $v),
						'size'=> $image[$k]['size'],
					];
				}
				try {
					Storage::deleteFiles($targets, $image['storage']);
				} catch(Exception $e) {
					throw new Exception("Can't delete image - Storage error: '" . $image['storage']['name']."'");
				}
			}
			
			if($update_user and $user_id) {
				$user = User::getSingle($user_id, 'id');
				DB::increment('users', ['image_count' => '-1'], ['id' => $user_id]);
			}
			
			// Update album count
			if($image['album']['id'] > 0) {
				Album::updateImageCount($image['album']['id'], 1, '-');
			}
			
			return DB::delete('images', ['id' => $id]);	
		} catch(Exception $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
	public static function deleteMultiple($ids) {
		if(!is_array($ids)) {
			throw new ImageException('Expecting array argument, '.gettype($ids).' given in '. __METHOD__, 100);
		}
		try {
			foreach($ids as $id) {
				self::delete($id);
			}
			return true;
		} catch(Excepton $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
	public static function fill(&$image) {

		$image['id_encoded'] = encodeID($image['id']);

		$targets = self::getSrcTargetSingle($image, false);
		
		// Recreate the thumb
		if($targets['type'] == 'path') {
			if(count($targets['chain']) > 0 && !$targets['chain']['thumb']) {
				try {
					$thumb_options = [
						'width'		=> getSetting('upload_thumb_width'),
						'height'	=> getSetting('upload_thumb_height'),
						'forced'	=> $image['extension'] == 'gif' and G\is_animated_image($targets['chain']['image'])
					];
					$targets['chain']['thumb'] = self::resize($targets['chain']['image'], pathinfo($targets['chain']['image'], PATHINFO_DIRNAME), $image['name'] . '.th', $thumb_options)['file'];
				} catch(Exception $e) {}
			}
			
			// Recreate the medium
			if($image['width'] > getSetting('upload_medium_width') && count($targets['chain']) > 0 && !$targets['chain']['medium']) {
				try {
					$medium_options = [
						'width'		=> getSetting('upload_medium_width'),
						'forced'	=> $image['extension'] == 'gif' and G\is_animated_image($targets['chain']['image'])
					];
					$targets['chain']['medium'] = self::resize($targets['chain']['image'], pathinfo($targets['chain']['image'], PATHINFO_DIRNAME), $image['name'] . '.md', $medium_options)['file'];
				} catch(Exception $e) {}
			}
			
			if(count($targets['chain']) > 0) {
				$image = array_merge($image, (array) @get_image_fileinfo($targets['chain']['image'])); // Never do an array merge over an empty thing!
			}
		} else {			
			$image_fileinfo = [
				'ratio'				=> $image['width'] / $image['height'],
				'size'				=> intval($image['size']),
				'size_formatted'	=> G\format_bytes($image['size'])
			];
			$image = array_merge($image, get_image_fileinfo($targets['chain']['image']), $image_fileinfo);
		}
		
		$image['file_resource'] = $targets;
		$image['url_viewer'] = self::getUrlViewer($image['id_encoded']);
		
		foreach($targets['chain'] as $k => $v) {
			if($targets['type'] == 'path') {
				$image[$k] = file_exists($v) ? get_image_fileinfo($v) : NULL;
			} else {
				$image[$k] = get_image_fileinfo($v);
			}
			$image[$k]['size'] = $image[($k == 'image' ? '' : $k . '_') . 'size'];
		}
		
		$display = $image['medium'] !== NULL ? $image['medium'] : ($image['size'] < G\get_bytes('500 KB') ? $image : $image['thumb']);
		$display_thumb = $display == $image['thumb'];

		$image['display_url'] = $display['url'];
		$image['display_width'] = $display_thumb ? getSetting('upload_thumb_width') : $image['width'];
		$image['display_height'] = $display_thumb ? getSetting('upload_thumb_height') : $image['height'];
		
		$image['views_label'] = _n('view', 'views', $image['views']);
		$image['how_long_ago'] = time_elapsed_string($image['date_gmt']);
		
		$image['title_truncated'] = G\truncate($image['title'], 22);
		
	}
	
	public static function formatArray($dbrow, $safe=false) {
		try {
			$output = DB::formatRow($dbrow);
			
			if(!is_null($output['user']['id'])) {
				User::fill($output['user']);
			} else {
				unset($output['user']);
			}

			if(!is_null($output['album']['id']) or !is_null($output['user']['id'])) {
				Album::fill($output['album'], $output['user']);
			} else {
				unset($output['album']);
			}
		
			self::fill($output);
		
			if($safe) {
				unset($output['storage']);
				unset($output['id'], $output['path'], $output['uploader_ip']);
				unset($output['album']['id'], $output['album']['privacy_extra']);
				unset($output['user']['id']);
				unset($output['file_resource']);
			}
		
			return $output;
			
		} catch(Excepton $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
}

class ImageException extends Exception {}