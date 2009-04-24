<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Reflect class
 *
 * @version	1
 * @package	Framework
 * @author	Luan Almeida <luanlmd@gmail.com>
 */
class Reflect 
{

	/**
	* Convert a mysql returned array to an object array
	*
	* @param	Recordset|string	$recordset		Recordset object or SQL query
	* @param	string				$type			Record object type
	* @return	array
	* @static
	*/
	static function createArray($recordset, $type="DTO")
	{
		if ($type == "") $type = ucfirst(controller);
		
		if (!is_object($recordset)) $recordset = Banco::getInstance()->consultar($recordset);
			
		$arr = array();
		foreach ($recordset->recordSet as $l)
		{
			$tmp = new $type;
			foreach ($l as $k => $v)
				if (!is_numeric($k)) $tmp->$k = $v;
			$arr[] = $tmp;
		}
		return $arr;
	}
	
	/**
	* Convert a mysql returned array with one record to an object array
	*
	* @param	Recordset|string	$recordset		Recordset object or SQL query
	* @param	string				$type			Record object type
	* @return	DTO
	* @static
	*/
	static function createObject($recordset, $type="DTO")
	{
		if ($type == "") $type = ucfirst(controller);
		if (!is_object($recordset))
		{
			$db = Banco::getInstance();
			$recordset = $db->consultar($recordset);
		}
		
		if ($recordset->numReg == 1)
		{
			$tmp = new $type;
			foreach($recordset->recordSet[1] as $k => $v)
				if (!is_numeric($k)) $tmp->$k = $v;
			return $tmp;
		}
		return false;
	}
}
