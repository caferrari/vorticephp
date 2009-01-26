<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * DTO class, Base for records objects
 *
 * @version	1
 * @package	Framework
 * @author	Luan Almeida <luanlmd@gmail.com>
 */
class DTO 
{

	protected static $dbInstance = 'default';

	/**
	* Set a record value
	*
	* @param	string			$name	new Field name
	* @param	string|mixed	$value	new Field name
	* @return	array
	*/
	public function __set($name, $value){
		if (method_exists($this, $name)){
			$metodo = "set" . ucfirst($name);
			$this->$metodo($value);
		}else
			$this->$name = $value;
	}
	
	/**
	* Get a record value
	*
	* @param	string	$name	Field name
	* @return	array
	*/
	public function __get($name){
		if (isset($this->$name))
			return $this->$name;
		if ($this->$name == NULL)
			return '';
		throw new UndefinedPropertyException($name);
	}

	/**
	* Escape the object params
	*
	* @return	void
	*/
	public function escape(){
		if(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) return;
		foreach (get_object_vars($this) as $p) $this->$p = addslashes($this->$p);
	}
	
	/**
	* Unescape the object params
	*
	* @return	void
	*/
	public function unescape(){
		foreach (get_object_vars($this) as $p) $this->$p = stripslashes($this->$p);
	}
	
}
