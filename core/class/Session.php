<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Route Class, decode routed requests
 *
 * @version	1
 * @package	Utils
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Session {
	/**
	* Session encrypt key
	*
	* @staticvar	string
	* @access		private
	*/
	private static $key = '';

	/**
	* Set the session key based on app title
	*
	* @return	void
	* @private
	*/
	private static function start(){
		try {
			session_start();
		}catch (Exception $e){ }
		self::$key = md5(apphash . session_id());
	}

	/**
	* Encrypt the session
	*
	* @return	boolean
	* @access	private
	* @static
	*/
	private static function encryptSession($arr){
		self::start();
		$_SESSION[self::$key] = Crypt::Encrypt(serialize($arr), self::$key);
		return true;
	}

	/**
	* Decrypt the session
	*
	* @return	array
	* @access	private
	* @static
	*/
	private static function decryptSession(){
		self::start();
		if (isset($_SESSION[self::$key]))
			return unserialize(Crypt::Decrypt($_SESSION[self::$key], self::$key));
		return array();
	}


	/**
	* Set a session value
	*
	* @param	string	$name	Session name
	* @param	mixed	$value	Session value
	* @return	bool
	* @static
	*/
	public static function set($name, $value){
		$arr = self::decryptSession();
		if (is_array($arr)) $arr[$name] = serialize($value);
		else $arr = array($name => $value);
		self::encryptSession($arr);
		return true;
	}
	
	/**
	* Delete a Session var
	*
	* @param	string	$name	Session name
	* @return	void
	* @static
	*/
	public static function del($name){
		self::set($name, false);
	}
	
	/**
	* Delete all sessions vars
	*
	* @return	void
	* @static
	*/
	public static function clear(){
		self::start();
		$_SESSION[self::$key] = false;
	}
	
	/**
	* Get a session value
	*
	* @param	string	$name	Session name
	* @return	string
	* @static
	*/
	public static function get($name){
		self::start();
		$arr = self::decryptSession();
		return isset($arr[$name]) ? @unserialize($arr[$name]) : '';
	}
}
