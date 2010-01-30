<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Json class, Json response conteiner controller
 *
 * @version	1
 * @package	Utils
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 * @author	Luan Almeida <luanlmd@gmail.com>
 */
class Json{
	
	/**
	* Temporary data array
	*
	* @var		array
	* @access	private
	*/
	private $json = array();
	
	/**
	* Singleton instance
	*
	* @staticvar	Json
	* @access		private
	* @static
	*/
	static $instance = false;
	
	/**
	* Constructor, Initialize the Json object
	*
	* @return	void
	*/
	private function __construct(){
		$this->json = array(
			'status' => 1,
			'message' => '',
			'errors' => array(),
			'packages' => array()
		);
	}
	
	/**
	* Load a Json object instance
	*
	* @return	Json
	*/
	public static function getInstance(){
		if (!self::$instance) self::$instance = new self();
		return self::$instance;
	}
	
	/**
	* Set json response sucess status
	*
	* @param	string	$status		New Status
	* @param	string	$mensagem	Status message
	* @return	bool
	*/
	public function set($status, $message='', $errors=''){
		if ($errors === '') $errors = array();
		$this->getInstance();
		$this->json['status'] = $status;
		$this->json['errors'] = $errors;
		if ($message!=='') $this->json['message'] = $message;
		return true;	
	}
	
	/**
	* Add data package
	*
	* @param	mixed	$name		Package name
	* @param	mixed	$values		Package values
	* @return	void
	*/
	public function addPackage($name, $values){
		if (!preg_match('@^_.*@', $name)) $this->json['packages'][$name] = $values;
	}
	
	/**
	* Convert data to json
	*
	* @return	string
	*/
	public function render(){
		foreach (DAO::getAll() as $k => $p) $this->addPackage($k, $p);
		return json_encode($this->json);
	}
}
