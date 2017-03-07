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

class Sftp {
	
	public $resource; // sftp session
		
	public function __construct($args=[]) {
		foreach(['server', 'user', 'password'] as $v) {
			if(!array_key_exists($v, $args)) {
				throw new SftpException("Missing $v value in ".__METHOD__, 100);
			}
		}
		
		$parsed_server = parse_url($args['server']);
		$host = $parsed_server['host'] ?: $args['server'];
		$port = $parsed_server['port'] ?: 22;
		
		require_once 'Net/SFTP.php';
		$this->resource = new \Net_SFTP($host, $port);
		
		if(!$this->resource) {
			throw new SftpException("Can't connect to ".$args['server']." server", 200);
		}
		if(!$this->resource->login($args['user'], $args['password'])) {
			throw new SftpException("Can't login to ".$args['server']." server", 301);
		}
		if(array_key_exists('path', $args)) {
			$this->resource->chdir($args['path']);
		}
		
		return $this;
	}
	
	public function close() {
		$this->resource->exec('exit');
		unset($this->resource);
		return true;
	}
	
	public function chdir($path) {
		$this->checkResource();
		if(!$this->resource->chdir($path)) {
			error_log("Can't change dir '$path' in " . __METHOD__);
			throw new SftpException("Can't change dir in " . __METHOD__, 300);
		}
	}
	
	public function put($args=[]) {
		foreach(['filename', 'source_file', 'path'] as $v) {
			if(!array_key_exists($v, $args)) {
				throw new SftpException("Missing $v value in ".__METHOD__, 100);
			}
		}
		$this->checkResource();		
		if(array_key_exists('path', $args) and !$this->resource->chdir($args['path'])) {
			error_log("Can't change dir '".$args['path']."' in " . __METHOD__);
			throw new SftpException("Can't change dir in " . __METHOD__, 200);
		}
		if(!$this->resource->put($args['filename'], $args['source_file'], NET_SFTP_LOCAL_FILE)) {
			error_log("Can't upload '".$args['filename']."' to '".$args['path']."' in " . __METHOD__);
			throw new SftpException("Can't upload '".$args['filename']."' in " . __METHOD__, 200);
		}
	}
	
	public function delete($file) {
		$this->checkResource();
		// Check if the file exists
		if(!$this->resource->stat($file[0])) {
			return true;
		}
		if(!$this->resource->delete($file)) {
			throw new SftpException("Can't delete file '$file' in " . __METHOD__, 200);
		}
	}
	
	public function deleteMultiple($files=[]) {
		$this->checkResource();
		if(!is_array($files) or count($files) == 0) {
			throw new SftpException("Missing or invalid array argument in " . __METHOD__, 200);
		}
		$cwd = G\add_ending_slash($this->resource->pwd());
		$rm_command = 'rm -f "' . $cwd . implode('" "' . $cwd, $files) . '"';
		$this->resource->exec($rm_command); // raw is war
		return $files;
	}
	
	
	public function mkdirRecursive($path) {
		$this->checkResource();
		return $this->resource->mkdir($path, -1, true);
	}
	
	private function checkResource() {
		if(!is_object($this->resource)) {
			throw new SftpException("Invaid SFTP object in " . __METHOD__, 200);
		}
	}
	
}

class SftpException extends Exception {}