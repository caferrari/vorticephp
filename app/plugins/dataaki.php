<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
/**
* Sample of a framework plugin to convert <!--plugin:data--> strings to the current date
* @package SampleApp
* @subpackage Plugins
*/

class Dataaki extends Template{

	/**
	* Execute the plugin
	*
	* @return	void
	*/
	public function __construct(){
		$data = date("d/m/Y");
		Template::setVar("plugin:data", $data);
		Template::mergeVars();
	}
	
	/**
	* Object to string
	*
	* @return	string
	*/
	public function __toString(){ return ""; }
}
