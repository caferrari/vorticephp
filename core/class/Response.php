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
		$function = 'render' . ucfirst($request['format']);
		if (function_exists($function))
			return $function($request);
		elseif (method_exists($this, $function))
			return $this->$function($request);
		else throw new Exception ('No support for ' . $request['format'] . ' format yet');
	}
	
	private function renderHtml($request){
		header('Content-Type: text/html; charset=utf-8'); 
		if (strstr($request['view'], ':'))
			$path = Vortice::getFw()->env->modulepath . 'view/' . str_replace(':', '/', $request['view'])  . '.php';
		else
			$path = Vortice::getFw()->env->modulepath . 'view/' . $request['controller'] . '/' . $request['view'] . '.php';
		
		if (!file_exists($path)) return;
		
		extract (self::$rs);
		include $path;		
	}
	
	private function renderText($request){
		$this->renderHtml($request);
		header('Content-Type: text/plain; charset=utf-8');
	}
	
	private function renderJson($request){
		header('Content-Type: application/json; charset=utf-8'); 
		exit (json_encode(self::getAll()));
	}
}
