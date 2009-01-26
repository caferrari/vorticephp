<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Security manager sample class
 * @package SampleApp
 * @subpackage SecuritySample
 */
class Seg{
	/**
	* Permissions object
	*
	* @staticvar	Object
	* @access		private
	* @static
	*/
	private static $perm;

	/**
	* Constructor
	*
	* @return	void
	*/
	public function __construct($arquivo=''){
		die ("Don't do that!!");
	}
	
	/**
	* Start the Seg engine
	*
	* @param	string	$arquivo	encoded permission file path
	* @return	void
	*/
	public function start($arquivo){
		if (file_exists($arquivo)){
			self::$perm = json_decode(base64_decode(Crypt::Decrypt(file_get_contents($arquivo), md5(tpl_title))));
		}else die ("perm file not found!");
	}
	
	/**
	* Check if user in a session named usuario haver permission
	* to see the requested action
	*
	* @param	string	$controller	Controller
	* @param	string	$action		Action
	* @return	bool
	*/
	public function verifica($controller=controller, $action=action){
		if (@Session::get("usuario")->tipo=='a') 	return true;
		
		if (is_object(@self::$perm->$controller))
			if (isset(self::$perm->$controller->$action))
				$p = self::$perm->$controller->$action;
			else throw (new Exception("permission not defined to: $controller:$action"));
		else
			if (isset(self::$perm->$controller))
				$p = @self::$perm->$controller;
			else
				throw (new Exception("permission not defined to controller: $controller"));
		
		if ($p=='') 	return true;
		if (!isset(Session::get("usuario")->id)) 	return false;
		return strstr($p, Session::get("usuario")->tipo) === false ? false : true;
	}

	/**
	* Check if user in a session named usuario haver permission
	* to see the requested action
	*
	* @return	void
	*/
	public function check(){
		if (!self::verifica())
			throw (new Exception("You dont have permission to execute that action!"));
	}
}
?>
