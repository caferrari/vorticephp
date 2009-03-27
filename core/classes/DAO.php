<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * DAO class, Conteiner for recordsets
 *
 * @version	1
 * @package	Framework
 * @author	Luan Almeida <luanlmd@gmail.com>
 */
class DAO 
{
	/**
	* Recordset conteiner
	*
	* @staticvar	array
	* @access		private
	*/
	private static $rs = array();
	
	/**
	* Return a recordset if exists
	*
	* @param	string	$index	Recordset nickname
	* @return	array
	*/
	public static function get($index='default')
	{
		return (isset(self::$rs[$index]))? self::$rs[$index] : NULL; 
	}
	
	/**
	* Return all recorsets
	*
	* @return	array
	*/
	public static function getAll(){
		return self::$rs;
	}
	
	/**
	* ADD a recordset to the conteiner and Return the recordset reference
	*
	* @param	string	$index	Recordset nickname
	* @return	array
	*/
	public static function &add($rs, $index='default')
	{
		self::$rs[$index] = $rs;
		return $rs;
	}
}
