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

class Album {
	
	public static function getSingle($id, $pretty=true) {
		$tables = DB::getTables();
		$query = 'SELECT * FROM '.$tables['albums']."\n";
		$joins = array(
			'LEFT JOIN '.$tables['users'].' ON '.$tables['albums'].'.album_user_id = '.$tables['users'].'.user_id'
		);
		$query .=  implode("\n", $joins) . "\n";
		$query .= 'WHERE album_id=:album_id;'."\n";
		try {
			$db = DB::getInstance();
			$db->query($query);
			$db->bind(':album_id', $id);
			$album_db = $db->fetchSingle();
			if(!$album_db) return false;
			return $pretty ? self::formatArray($album_db) : $album_db;
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
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
			throw new AlbumException('Null ids provided in ' . __METHOD__, 100);
		}
		
		$tables = DB::getTables();
		$query = 'SELECT * FROM '.$tables['albums']."\n";
		$joins = array(
			'LEFT JOIN '.$tables['users'].' ON '.$tables['albums'].'.album_user_id = '.$tables['users'].'.user_id'
		);
		
		$query .=  implode("\n", $joins) . "\n";
		$query .= 'WHERE album_id IN ('. join(',', $ids). ')' . "\n";
		
		try {
			$db = DB::getInstance();
			$db->query($query);
			$db_rows = $db->fetchAll();
			if($pretty) {
				$return = [];
				foreach($db_rows as $k => $v) {
					$return[$k] = self::formatArray($v);
				}
				return $return;
			}
			return $db_rows;
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
		}
		
	}
	
	public static function getUrl($album_id) {
		return G\get_base_url('album/'.$album_id);
	}
	
	public static function insert($name, $user_id, $privacy='public', $description='') {
		if(!$user_id) {
			throw new AlbumException('Missing $user_id', 100);
		}
		
		if(!$name) {
			$name = _s('Untitled') . ' ' . G\datetime();
		}
		
		if(!in_array($privacy, array('public', 'private', 'private_but_link'))) {
			$privacy = 'public';
		}
		
		G\nullify_string($description);
		
		$album_array = array(
			'name'		=> $name,
			'user_id'	=> $user_id,
			'date'		=> G\datetime(),
			'date_gmt'	=> G\datetimegmt(),
			'privacy'	=> $privacy,
			'description'	=> $description
		);
		
		try {
			$insert = DB::insert('albums', $album_array);
			$user = User::getSingle($user_id, 'id');
			DB::increment('users', ['album_count' => '+1'], ['id' => $user_id]);
			return $insert;
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
		}
	}
	
	// Move contents $from albums to another album
	public static function moveContents($from, $to) {
		
		if(!$from) { // Could be int or array (multiple)
			throw new AlbumException('Expecting first parameter, '.gettype($from).' given in ' . __METHOD__, 100);
		}
		
		if(!$to) { 
			$to = NULL;
		}
		
		$ids = is_array($from) ? $from : array($from);

		try {
			$db = DB::getInstance();
			$db->query('UPDATE '.DB::getTable('images').' SET image_album_id=:image_album_id WHERE image_album_id IN ('.implode(',', $ids).')');
			$db->bind(':image_album_id', $to);
			$images = $db->exec();
			if($images) {
				$images_affected = $db->rowCount();
				// Update the old and new albums to +ids
				$db->query(
					'UPDATE '.DB::getTable('albums').' SET album_image_count = 0 WHERE album_id IN ('.implode(',', $ids).');' . 
					'UPDATE '.DB::getTable('albums').' SET album_image_count = album_image_count + '.$images_affected.' WHERE album_id=:album_id;'
				);
				$db->bind(':album_id', $to);
				$db->exec();
			} else {
				return false;
			}
			return true;
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
		}
	}
	
	public static function addImage($album_id, $id) {
		return self::addImages($album_id, array($id));
	}
	
	public static function addImages($album_id, $ids) {
		
		// $album_id can be null.. Remember the user stream
			
		if(!is_array($ids) or count($ids) == 0) {
			throw new AlbumException('Expecting array values, '.gettype($values).' given in ' . __METHOD__, 100);
		}
		
		try {
			
			// Get the images
			$images = Image::getMultiple($ids, true);
			
			// Get the albums
			$albums = [];
				
			foreach($images as $k => $v) {
				if($v['album']['id'] and $v['album']['id'] !== $album_id) {
					$album_k = $v['album']['id'];
					if(!array_key_exists($album_k, $albums)) {
						$albums[$album_k] = [];
					}
					$albums[$album_k][] = $v['id'];
				}
			}
	
			$db = DB::getInstance();
			$db->query('UPDATE `'.DB::getTable('images').'` SET `image_album_id`=:image_album_id WHERE `image_id` IN ('.implode(',', $ids).')');
			$db->bind(':image_album_id', $album_id);
			$exec = $db->exec();
			if($exec and $db->rowCount() > 0) {
				// Update the new album
				if(!is_null($album_id)) {
					self::updateImageCount($album_id, $db->rowCount());
				}
				// Update the old albums
				if(count($albums) > 0) {
					$album_query = '';
					$album_query_tpl = 'UPDATE `'.DB::getTable('albums').'` SET `album_image_count` = `album_image_count` - :counter WHERE `album_id` = :album_id;';
					/*
					$db->beginTransaction(); // nota
					$db->query('UPDATE `'.DB::getTable('albums').'` SET `album_image_count` = `album_image_count` - :counter WHERE `album_id` = :album_id;');
					*/
					foreach($albums as $k => $v) {
						$album_query .= strtr($album_query_tpl, [':counter' => count($v), ':album_id' => $k]);
						/*
						$db->bind(':counter', count($v));
						$db->bind(':album_id', $k);
						$db->exec();
						*/
					}
					$db = DB::getInstance();
					$db->query($album_query);
					$db->exec();
					/*
					$db->endTransaction();
					*/
				}
			}
			return $exec;
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
		}
		
	}
	
	public static function update($id, $values) {
		if(array_key_exists('description', $values)) {
			G\nullify_string($values['description']);
		}
		try {
			return DB::update('albums', $values, array('id'=>$id));
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
		}
	}
		
	// Delete album, return the number of deleted images
	public static function delete($id) {
		try {
			
			// Get the user id
			$user_id = DB::get('albums', ['id' => $id])[0]['album_user_id'];
			
			// Delete album, the easy part
			$delete = DB::delete('albums', ['id' => $id]);
			
			if(!$delete) return false;
			
			// Delete album images
			$db = DB::getInstance();
			$db->query('SELECT image_id FROM ' . DB::getTable('images') . ' WHERE image_album_id=:image_album_id');
			$db->bind(':image_album_id', $id);
			$album_image_ids = $db->fetchAll();
			
			// Delete the files
			$images_deleted = 0;
			foreach($album_image_ids as $k => $v) {
				if(Image::delete($v['image_id'], false)) { // We will update the user counts (image + album) at once
					$images_deleted++;
				}
			}
			
			// Update the user
			$user = User::getSingle($user_id, 'id');
			$user_updated_counts = [
				'album_count' => '-1',
				'image_count' => '-' . $images_deleted
			];
			/*
			foreach($user_updated_counts as $k => &$v) {
				if($v < 0) $v = 0;
			}
			*/
			DB::increment('users', $user_updated_counts, ['id' => $user_id]);			
			return $images_deleted;
			
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
		}
	}
	
	public static function deleteMultiple($ids) {
		if(!is_array($ids)) {
			throw new AlbumException('Expecting array argument, ' . gettype($ids) . ' given in ' . __METHOD__, 100);
		}
		$affected = 0;
		foreach($ids as $id) {
			$affected += self::delete($id);
		}
		return $affected;
	}
	
	public static function updateImageCount($id, $counter=1, $operator='+') {
		try {
			$query = 'UPDATE `'.DB::getTable('albums').'` SET `album_image_count` = ';
			if(in_array($operator, ['+', '-'])) {
				$query .= '`album_image_count` ' . $operator . ' ' . $counter;
			} else {
				$query .= $counter;
			}
			$query .= ' WHERE `album_id` = :album_id';
			$db = DB::getInstance();
			$db->query($query);
			$db->bind(':album_id', $id);
			$exec = $db->exec();
			return $exec;
		} catch(Exception $e) {
			throw new AlbumException($e->getMessage(), 400);
		}
	}
	
	public static function fill(&$album, &$user=[]) {
		$album['id_encoded'] = $album['id'] ? encodeID($album['id']) : NULL;
		if($user['id'] !== NULL) {
			if($album['name'] == NULL) {
				$album['name'] = _s("%s's images", $user['name_short']);
			}
			$album['url'] = $album['id'] == NULL ? User::getUrl($user['username']) : self::getUrl($album['id_encoded']);
		}
		if($album['privacy'] == NULL) {
			$album['privacy'] = "public";
		}
		if(!empty($user)) {
			User::fill($user);
		}
	}
	
	public static function formatArray($dbrow, $safe=false) {
		try {
			$output = DB::formatRow($dbrow);
			self::fill($output, $output['user']);
			$output['how_long_ago'] = time_elapsed_string($output['date_gmt']);
	
			if($output['images_slice']) {
				foreach($output['images_slice'] as $k => $v) {
					$output['images_slice'][$k] = Image::formatArray($output['images_slice'][$k]);
				}
			}
	
			if($safe) {
				unset($output['id'], $output['privacy_extra']);
				unset($output['user']['id']);
			}
	
			return $output;
		} catch(Excepton $e) {
			throw new ImageException($e->getMessage(), 400);
		}
	}
	
}

class AlbumException extends Exception {}