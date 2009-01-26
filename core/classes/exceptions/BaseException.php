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
class BaseException extends Exception{
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
	public function __construct($message, $details='', $errorfile=''){
		$message = e($message);
		$this->details = $details;
		$this->errorfile = $errorfile;
		parent::__construct($message);
		$this->log();
	}

	/**
	* Log an error
	*
	* @return	void
	*/
	public function log(){
		global $_PAR;
		
		if (defined("logs_dir")) $log_dir = logs_dir;
		else $log_dir = rootfisico . "logs";
		
		if (is_writeable($log_dir)){
			$debug_log_file = rootfisico . "logs/debug/" . md5($this->__toString()) . ".log";
			$nome_log = get_class($this) . "-" . date("Y-m-d") . ".log";
		
			$debug_log = "uri: " . uri . "\npars: " . json_encode($_PAR) . "\nrequest: " . json_encode($_REQUEST) . "\n";
			$debug_log .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\nMessage: {$this->message} -> {$this->details}\n\n" . parent::__toString();
		
			@mkdir(rootfisico . "logs/debug");
			file_put_contents($debug_log_file, $debug_log);
		}
	}
	
	/**
	* Convert an error class to a string
	*
	* @return	string
	*/
	public function __toString(){
		$file = rootfisico . "app/error_docs/{$this->errorfile}.html";
		if (file_exists($file)){
			$arquivo = file_get_contents($file);
			$arquivo = str_replace("{details}", $this->details, $arquivo);
			$arquivo = str_replace("{message}", $this->message, $arquivo);
			$arquivo = str_replace("{code}", $this->code, $arquivo);
			$arquivo = str_replace("{file}", $this->file, $arquivo);
			$arquivo = str_replace("{trace}", parent::__toString(), $arquivo);
			return $arquivo;
		}else{
			return parent::__toString();
		}
	}
}
