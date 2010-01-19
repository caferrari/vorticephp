<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * BaseException Class, to logs and control exceptions on the framework
 *
 * @version	1
 * @package	Exceptions
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class VorticeException extends Exception{
	/**
	* Error details
	*
	* @var	string
	* @static
	* @access protected
	*/
	protected $details;
	
	/**
	* Error file
	*
	* @var	string
	* @static
	* @access protected
	*/
	protected $errorfile;

	/**
	* Constructor, create an error object
	*
	* @param	string	$message	Error basic message
	* @param	string	$details	Error details
	* @param	string	$errorfile	Error file to be loaded
	* @return	void
	*/
	public function __construct($message, $details='', $errorfile='404'){
		$message = e($message);
		$this->details = $details;
		$this->errorfile = $errorfile;
		parent::__construct($message);
		$this->trace = parent::__toString();
	}

	/**
	* Log an error
	*
	* @return	void
	*/
	public function log(){
		global $_PAR;
		
		if (defined("logs_dir")) $log_dir = logs_dir;
		else $log_dir = root . "logs";
		@mkdir($log_dir);
		if (is_writeable($log_dir)){
			$debug_log_file = "$log_dir/debug/" . md5($this->trace) . ".log";
			$nome_log = get_class($this) . "-" . date("Y-m-d") . ".log";
		
			$debug_log = "uri: " . uri . "\npars: " . json_encode($_PAR) . "\nrequest: " . json_encode($_REQUEST) . "\n";
			$debug_log .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\nMessage: {$this->message} -> {$this->details}\n\n" . parent::__toString();
		
			@mkdir(root . "logs/debug");
			file_put_contents($debug_log_file, $debug_log);
		}
	}
	
	public function setFile($file){
		$this->file = $file;
	}
	
	/**
	* Convert an error class to a string
	*
	* @return	string
	*/
	public function __toString(){
		$this->log();
	
		$headers = array(
			'400' => 'HTTP/1.1 400 Bad Request',
			'403' => 'HTTP/1.1 403 Forbidden',
			'404' => 'HTTP/1.1 404 Not Found',
			'500' => 'HTTP/1.1 500 Internal Server Error'
		);
		header($headers[$this->errorfile]);

		$file1 = root . "app/error_docs/{$this->errorfile}.html";
		$file2 = root . "app/error_docs/error.html";
		$file3 = root . "core/error_docs/error.html";
		if (file_exists($file1)) 
			$arquivo = file_get_contents($file1);
		elseif (file_exists($file2)) 
			$arquivo = file_get_contents($file2);
		elseif  (file_exists($file3)) 
			$arquivo = file_get_contents($file3);
		else exit ("error");
					
		$arquivo = str_replace("{details}", $this->details, $arquivo);
		$arquivo = str_replace("{message}", $this->message, $arquivo);
		$arquivo = str_replace("{code}", $this->errorfile, $arquivo);
		$arquivo = str_replace("{file}", $this->file, $arquivo);
		$arquivo = str_replace("{trace}", $this->trace, $arquivo);
		
		exit($arquivo);
	}
}
