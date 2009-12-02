<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * DTO class, Base for records objects
 *
 * @version	1
 * @package	Database
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
	public static function escape(){
		if(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) return;
		foreach (get_object_vars($this) as $p) $this->$p = addslashes($this->$p);
	}
	
	/**
	* Unescape the object params
	*
	* @return	void
	*/
	public static function unescape(){
		foreach (get_object_vars($this) as $p) $this->$p = stripslashes($this->$p);
	}
	
	/**
	* Create an array with the given properties
	*
	* @return 	array
	*/
	public function toArray($fields=null){
		$fields = explode(",", preg_replace("/[ ]+/", "", $fields));
		
		$tmp = array();
		foreach ($fields as $f){
			if (isset($this->$f)) $tmp[] = $this->$f;
			else $tmp[]=null;
		}
	
		return $tmp;
	}
	
	/**
	* Insert data into the Database
	*
	* @return 	int
	*/
	public function insert($fields, $table=null, $instance='default'){
		$table = uncamelize(($table == null) ? (isset($this->_table) ? $this->_table : get_class($this)) : $table);
		$values = substr(str_repeat("?,", count(explode(",", $fields))), 0, -1);
		$sql = "INSERT INTO $table ($fields) VALUES ($values);";
		$id = Database::getInstance($instance)->exec($sql, $this->toArray($fields));
		return $id;
	}
	
	/**
	* Update data in the database
	*
	* @return 	dto
	*/
	public function save($fields, $table=null, $instance='default'){
		if (!isset($this->id) || !is_numeric($this->id)) return $this->insert($fields, $table, $instance);
		$table = uncamelize(($table == null) ? (isset($this->_table) ? $this->_table : get_class($this)) : $table);
		$id = $this->id;
		unset($this->id);
		$values = $this->toArray($fields);
		$values[] = $this->id = $id;
		$tmp = array();
		$fields = explode(",", $fields);
		foreach ($fields as $k=>$f) $tmp[] = $fields[$k] . "=?";
		$sql = "UPDATE $table SET " . implode($tmp, ', ') . " WHERE id=?";
		return Database::getInstance($instance)->exec($sql, $values);
	}

}
