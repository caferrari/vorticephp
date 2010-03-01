<?php
/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Response class, Conteiner for recordsets
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.com.br>
 */
class Response 
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
	public static function &getAll(){
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
	
	public function __construct($request){
		$function = 'render_' . $request['format'];
		if (method_exists($this, $function)){
			return $this->$function($request);
		}elseif (function_exists($function)){
			return $function($request);
		}else throw new Exception ('No support for ' . $request['format'] . ' format yet');
	}
	
	private function render_html($request){
		die ('html response!');
	}
	
	private function render_json($request){
		exit (json_encode(self::getAll()));
	}
}
