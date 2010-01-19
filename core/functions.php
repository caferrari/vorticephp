<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
* Framework functions
* @package Framework
*/

/**
* auto load classes
* @param	$class	classname
* @return	void
*/
function __autoload($class)
{
	if (class_exists($class, false) || interface_exists($class, false)) return;

	$folders = array(
		"core/classes/",
		"core/classes/exceptions",
		"app/classes/",
		"app/model/",
		"app/facade/",
		"app/controller/",
		"app/helper/",
		"core/helper/"
    );
    
	if (defined("module")){
		$m = module;
		array_unshift($folders, "app/modules/$m/model/", "app/modules/$m/controller/");
	}

	foreach ($folders as $f)
		if (file_exists(root . "{$f}/{$class}.php")) { include_once(root . "{$f}/{$class}.php"); return; }
    
 	//throw (new Exception("Class not found: $class"));
}

/**
* Check if the request is from a mobile phone
* @return	boolean
*/
function check_lib($filename, $base){
	$path = root . "core/lib/$filename";
	if (!file_exists($path)) return false;
	$base = strtolower($base);
	$found = false;
	$file = fopen("$path","r");
	$l = fgets($file, 128);
	if (!trim($l)) return false;
	do {
		$found = (strpos($base, $l) !== false);
		$l = trim(fgets($file, 128));
	} while (!$found && !feof($file));
	return $found ? true : false;
}


/**
* Check if the request is from a mobile phone
* @return	boolean
*/
function is_mobile(){
	$mobile = isset($_SESSION["vortice-mobile"]) ? $_SESSION["vortice-mobile"] : "";
	if ($mobile === ""){
		$base = isset($_SERVER['HTTP_X_OPERAMINI_PHONE']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE'] : 
			isset($_SERVER['X-OperaMini-Phone-UA']) ? $_SERVER['X-OperaMini-Phone-UA'] :
			isset($_SERVER['X-OperaMini-Phone']) ? $_SERVER['X-OperaMini-Phone'] : '';
		$base .= isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
		$base .= isset($_SERVER['HTTP_ACCEPT']) ? strtolower($_SERVER['HTTP_ACCEPT']) : '';
		$mobile = check_lib("mobile-strings", $base);
		$_SESSION["vortice-mobile"] = $mobile;
	}
	return $mobile;
}

/**
* Check if is a Search Engine bot request
* @return	boolean
*/
function is_bot(){
	$tmp = '66.249.65.39' . isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
	return check_lib("bot-strings", $tmp);
}

/**
* get a posted value
* @param	$v	variable name
* @return	string
*/
function p($v){
	global $_POST, $_PAR;
	return isset($_POST[$v]) ? $_POST[$v] : (isset($_PAR[$v]) ? $_PAR[$v] : '');
}

/**
* initalize and get URL parts
* @param	$i	url part index
* @return	string
*/
function u($i){
	if (!defined("uri_parts")){
		preg_match_all("@([^/]+):([^/]+)|([^/]+)@", uri, $mat, PREG_SET_ORDER);
		$u = array();
		foreach ($mat as $k => $v){
			$u[$k] = $v[0];
			if (count($v) == 3) $u[$v[1]] = $v[2];	
		}
		define ("uri_parts", serialize($u));
	}else
		$u = unserialize(uri_parts);

	return isset($u[$i]) ? $u[$i] : '';
}

/**
* Redirect the response
* @param	$destino	Destination url encoded with Link class
* @param	$delay		Delay
* @return	void
*/
function redirect($destino="", $delay=0){
	if (ajax){
		$json = Json::getInstance();
		$json->addPackage("redirect", urlencode($destino));
		exit($json->render());
	}else
		exit("<html><head><meta http-equiv=\"refresh\" content=\"$delay;URL=$destino\"></head><body></body></html>");
}

/**
* Reset a vector keys
* @param	$arr		Array
* @return	array
*/
function reset_keys(&$arr){
	$new = array();
	foreach ($arr as $i) $new[] = $i;
	return $new;
}

/**
* Convert sala-de-imprensa to SalaDeImprensa
* @param	$str		string
* @return	string
*/
function camelize($str='') {
    return str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $str)));
}

/**
* Convert SalaDeImprensa to sala_de_imprensa
* @param	$str		string
* @return	string
*/
function uncamelize($str=''){
	return preg_replace('@^_+|_+$@', '', strtolower(preg_replace("/([A-Z])/", "_$1", $str)));
}

/**
* Convert the first char to lower case
* @param	$str		string
* @return	string
*/
if (!function_exists("lcfirst")){
	function lcfirst($str="") {
		if ($str=='') return '';
		$str{0} = strtolower($str{0});
    	return $str;
	}
}

/**
* Get current microtime
* @return	float
*/
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function d($v){
	die(print_r($v));
}

/**
* Translate a phrase using I18n class
* @param	mixed
* @return	string
*/
function e(){
	return I18n::e(func_get_args());
}
