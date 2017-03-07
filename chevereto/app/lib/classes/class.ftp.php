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

class Ftp {
	
	public $resource;
		
	public function __construct($args=[]) {
		foreach(['server', 'user', 'password'] as $v) {
			if(!array_key_exists($v, $args)) {
				throw new FtpException("Missing $v value in ".__METHOD__, 100);
			}
		}
		
		$parsed_server = parse_url($args['server']);
		$host = $parsed_server['host'] ?: $args['server'];
		$port = $parsed_server['port'] ?: 21;
		
		$this->resource = @ftp_connect($host, $port);	
		if(!$this->resource) {
			throw new FtpException("Can't connect to ".$args['server']." server", 200);
		}		
		if(!@ftp_login($this->resource, $args['user'], $args['password'])) {
			throw new FtpException("Can't login to ".$args['server']." server", 201);
		}
		$args['passive'] = array_key_exists('passive', $args) ? (bool)$args['passive'] : true;
		if(!@ftp_pasv($this->resource, $args['passive'])) {
			throw new FtpException("Can't ".($args['passive'] ? "enable" : "disable")." passive mode in server ".$args['server'], 202);
		}

		if(array_key_exists('path', $args)) {
			$this->chdir($args['path']);
		}
		return $this;
	}
	
	public function close() {
		@ftp_close($this->resource);
		unset($this->resource);
		return true;
	}
	
	public function chdir($path) {
		$this->checkResource();
		if(!@ftp_chdir($this->resource, $path)) {
			error_log("Can't change dir '$path' in " . __METHOD__);
			throw new FtpException("Can't change dir in " . __METHOD__, 300);
		}
	}
	
	public function put($args=[]) {
		foreach(['filename', 'source_file', 'path'] as $v) {
			if(!array_key_exists($v, $args)) {
				throw new FtpException("Missing $v value in ".__METHOD__, 100);
			}
		}
		if(!array_key_exists('method', $args) or !in_array($args['method'], [FTP_BINARY, FTP_ASCII])) {
			$args['method'] = FTP_BINARY;
		}
		$this->checkResource();
		if(array_key_exists('path', $args) and !@ftp_chdir($this->resource, $args['path'])) {
			error_log("Can't change dir '".$args['path']."' in " . __METHOD__);
			throw new FtpException("Can't change dir in " . __METHOD__, 200);
		}
		if(!@ftp_put($this->resource, $args['filename'], $args['source_file'], $args['method'])) {
			error_log("Can't upload '".$args['filename']."' to '".$args['path']."' in " . __METHOD__);
			throw new FtpException("Can't upload '".$args['filename']."' in " . __METHOD__, 200);
		}
	}
	
	public function delete($file) {
		$this->checkResource();
		
		// Force binary mode
		$binary = ftp_raw($this->resource, 'TYPE I'); // SIZE command works only in Binary
		
		// Check if the file exists
		$raw = ftp_raw($this->resource, "SIZE $file")[0];
		
		preg_match('/^(\d+)\s+(.*)$/', $raw, $matches);
		$code = $matches[1];
		$return = $matches[2];
		if($code>500) { // SIZE is supported and the file doesn't exits
			return;
		}
		if(!@ftp_delete($this->resource, $file)) {
			throw new FtpException("Can't delete file '$file' in " . __METHOD__, 200);
		}
	}
	
	
	public function mkdirRecursive($path) {
		$this->checkResource();
		$cwd = @ftp_pwd($this->resource);
		if(!$cwd) {
			throw new FtpException("Can't get current working directory in " . __METHOD__, 200);
		}
		$cwd .= '/';
		foreach(explode('/', $path) as $part){
			$cwd .= $part . '/';
			if(empty($part)){
				continue;
			}
			if(!@ftp_chdir($this->resource, $cwd)){
				if(@ftp_mkdir($this->resource, $part)){
					@ftp_chdir($this->resource, $part);
				} else {	
					error_log("Can't make recursive dir '$path' relative to '$cwd' in " . __METHOD__);
					throw new FtpException("Can't make recursive dir in " . __METHOD__, 200);
					return false;
				}
			}
		}
	}
	
	private function checkResource() {
		if(!is_resource($this->resource)) {
			throw new FtpException("Invaid FTP buffer in " . __METHOD__, 200);
		}
	}
	
}

class FtpException extends Exception {}