<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Route Class, decode routed requests
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Route{
	/**
	* Rotutes conteiner
	*
	* @staticvar	array
	* @access		private
	*/
	private static $routes = array();

	/**
	* Add a route to the conteiner
	*
	* @param	string	$er		Regular Expression for te route
	* @param	string	$ac		Action to redirect to [module]+[controller:[action]]
	* @param	string	$pars	Route parameters
	* @return	string
	* @static
	*/
	public static function add($er, $ac, $pars=''){
		$er = "/" . addcslashes($er, "/") . "/";
		self::$routes[] = array("er" => $er, "ac" => $ac, "pars" => $pars);
	}
	
	/**
	* Test requested URI and return the json encoded url to the Link class
	*
	* @return	string
	* @static
	*/
	public static function exec(){
		foreach (self::$routes as $r){
			if (preg_match($r['er'], uri, $match)){
				$p = $r['pars'];
				for ($x=1; $x<count($match); $x++) $p = str_replace("%$x", $match[$x], $p);
				$p = preg_replace("/%[0-9]+/", "", $p);				
				parse_str($p, $pars);
				define ("routed", true);
				return json_decode(json_encode(array(
					"url" => $r['ac'],
					"pars" => $pars
				)));
			}
		}
		define ("routed", false);
		return false;
	}
}
