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
	    "app/controller/"
    );
    
	if (defined("module")){
		$m = module;
		$folders[] = "app/modules/$m/model/";
		$folders[] = "app/modules/$m/controller/";
	}

	foreach ($folders as $f)
		if (file_exists(rootfisico . "{$f}/{$class}.php")) { require_once(rootfisico . "{$f}/{$class}.php"); return; }
    
 	//throw (new Exception("Class not found: $class"));
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
* Redirect the response
* @param	$destino	Destination url encoded with Link class
* @param	$delay		Delay
* @return	void
*/
function redirect($destino="", $delay=0){
	//if (!ereg("^/", $destino) && !ereg("^http://", $destino)) $destino = "/$destino";
	if (ajax){
		$json = Json::getInstance();
		$json->addPacote(array("redirect", $destino, $delay));
		die($json->render());
	}else
		die ("<html><head><meta http-equiv=\"refresh\" content=\"$delay;URL=$destino\"></head><body></body></html>");
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
* Translate a phrase using I18n class
* @param	mixed
* @return	string
*/
function e(){
	return I18n::e(func_get_args());
}
