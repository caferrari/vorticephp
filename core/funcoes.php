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
	if (class_exists($class, false) || interface_exists($class, false)) {
        return;   
    }
    
    $folders = array(
	    "core/classes/",
	    "core/classes/exceptions",
	    "app/classes/",
	    "app/model/",
	    "app/controller/",
		"app/helper/",
		"core/helper/"
    );
    
	if (defined("module")){
		$m = module;
		array_unshift($folders, "app/modules/$m/model/", "app/modules/$m/controller/");
	}

	foreach ($folders as $f)
		if (file_exists(rootfisico . "{$f}/{$class}.php")) { require_once(rootfisico . "{$f}/{$class}.php"); return; }
    
 	//throw (new Exception("Class not found: $class"));
}

/**
* Check if the request is from a mobile phone
* @return	boolean
*/
function is_mobile(){
	$op = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE'] : '');
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
	$ac = isset($_SERVER['HTTP_ACCEPT']) ? strtolower($_SERVER['HTTP_ACCEPT']) : '';
	return strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
		|| $op != ''
		|| strpos($ua, 'iphone') !== false
		|| strpos($ua, 'android') !== false
		|| strpos($ua, 'symbian') !== false 
		|| strpos($ua, 'htc') !== false
		|| strpos($ua, 'blackberry') !== false
		|| strpos($ua, 'sprint') !== false    
		|| strpos($ua, 'nokia') !== false 
		|| strpos($ua, 'sony') !== false 
		|| strpos($ua, 'wap') !== false
		|| strpos($ua, 'samsung') !== false 
		|| strpos($ua, 'mobile') !== false
		|| strpos($ua, 'windows ce') !== false
		|| strpos($ua, 'epoc') !== false
		|| strpos($ua, 'opera mini') !== false
		|| strpos($ua, 'nitro') !== false
		|| strpos($ua, 'j2me') !== false
		|| strpos($ua, 'midp-') !== false
		|| strpos($ua, 'cldc-') !== false
		|| strpos($ua, 'netfront') !== false
		|| strpos($ua, 'mot') !== false
		|| strpos($ua, 'up.browser') !== false
		|| strpos($ua, 'up.link') !== false
		|| strpos($ua, 'audiovox') !== false
		|| strpos($ua, 'ericsson') !== false
		|| strpos($ua, 'panasonic') !== false
		|| strpos($ua, 'philips') !== false
		|| strpos($ua, 'sanyo') !== false
		|| strpos($ua, 'sharp') !== false
		|| strpos($ua, 'sie-') !== false
		|| strpos($ua, 'portalmmm') !== false
		|| strpos($ua, 'blazer') !== false
		|| strpos($ua, 'avantgo') !== false
		|| strpos($ua, 'danger') !== false
		|| strpos($ua, 'palm') !== false
		|| strpos($ua, 'series60') !== false
		|| strpos($ua, 'palmsource') !== false
		|| strpos($ua, 'pocketpc') !== false
		|| strpos($ua, 'smartphone') !== false
		|| strpos($ua, 'rover') !== false
		|| strpos($ua, 'ipaq') !== false
		|| strpos($ua, 'au-mic,') !== false
		|| strpos($ua, 'alcatel') !== false
		|| strpos($ua, 'ericy') !== false
		|| strpos($ua, 'up.link') !== false
		|| strpos($ua, 'vodafone/') !== false;
}

/**
* Check if is a Search Engine bot request
* @return	boolean
*/
function is_bot(){
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
	$ip = $_SERVER['REMOTE_ADDR'];
	return $ip == '66.249.65.39' 
		|| strpos($ua, 'googlebot') !== false 
		|| strpos($ua, 'mediapartners') !== false 
		|| strpos($ua, 'yahooysmcm') !== false 
		|| strpos($ua, 'baiduspider') !== false
		|| strpos($ua, 'msnbot') !== false
		|| strpos($ua, 'slurp') !== false
		|| strpos($ua, 'ask') !== false
		|| strpos($ua, 'teoma') !== false
		|| strpos($ua, 'spider') !== false 
		|| strpos($ua, 'heritrix') !== false 
		|| strpos($ua, 'attentio') !== false 
		|| strpos($ua, 'twiceler') !== false 
		|| strpos($ua, 'irlbot') !== false 
		|| strpos($ua, 'fast crawler') !== false                        
		|| strpos($ua, 'fastmobilecrawl') !== false 
		|| strpos($ua, 'jumpbot') !== false
		|| strpos($ua, 'googlebot-mobile') !== false
		|| strpos($ua, 'yahooseeker') !== false
		|| strpos($ua, 'motionbot') !== false
		|| strpos($ua, 'mediobot') !== false
		|| strpos($ua, 'chtml generic') !== false;
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
		$json->addPackage("redirect", array($destino, $delay));
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
	$arr = $new;
	return $arr;
}

/**
* Convert sala-de-imprensa to SalaDeImprensa
* @param	$str		string
* @return	string
*/
function camelize($str="") {
    return str_replace(" ", "", ucwords(str_replace(array("_", "-"), " ", $str)));
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

/**
* Translate a phrase using I18n class
* @param	mixed
* @return	string
*/
function e(){
	return I18n::e(func_get_args());
}
