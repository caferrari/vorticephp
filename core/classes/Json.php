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
	static $instancia = false;
	
	/**
	* Constructor, Initialize the Json object
	*
	* @return	void
	*/
	private function __construct(){
		$this->json = array(
			"status" => 1,
			"mensagem" => "",
			"pacotes" => array()
		);
	}
	
	/**
	* Load a Json object instance
	*
	* @return	Json
	*/
	public static function getInstance(){
		if (!self::$instancia) self::$instancia = new self();
		return self::$instancia;
	}
	
	/**
	* Set json response sucess status
	*
	* @param	string	$status		New Status
	* @param	string	$mensagem	Status message
	* @return	bool
	*/
	public function set($status, $mensagem=""){
		$this->getInstance();
		$this->json["status"] = $status;
		if ($mensagem!="") $this->json["mensagem"] = $mensagem;
		return true;	
	}
	
	/**
	* Add data package
	*
	* @param	mixed	$name		Package name
	* @param	mixed	$values		Package values
	* @return	void
	*/
	public function addDAO($name, $values){
		$this->json["pacotes"][$name] = $values;
	}
	
	/**
	* Add data package
	*
	* @param	mixed	$pct		Package
	* @return	void
	*/
	public function addPacote($pct){
		$this->json["pacotes"][] = $pct;
	}
	
	/**
	* Convert data to json
	*
	* @return	string
	*/
	public function render(){
		return json_encode($this->json);
	}
}
