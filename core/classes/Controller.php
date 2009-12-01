<?php

/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>
 * All rights reserved. 
 */

/**
 * Framework Controller class
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Controller {
	/**
	* Store data to be used in the view
	*
	* @var		string
	* @var		string
	* @access	public
	*/
	public function __set($met, $val){
		if ($met == '_view') 		Template::setView($val);
		if ($met == '_template') 	Template::setTemplate($val);
		DAO::add($val, $met);
		$this->$met = $val;
	}
	
	/**
	* Retrive the object property or a DAO data
	*
	* @var		string
	* @access	public
	*/
	public function __get($met){
		if (isset($this->$met)) return $this->$met;
		return DAO::get("$met");	
	}
}
