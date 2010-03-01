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
		if (!preg_match ('@^(pars|_view|_template|_format)$@', $met))
			Response::add($val, $met);
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
		return Response::get("$met");	
	}
	
	public function _setvar ($met, $val){
		$this->$met = '';
		$this->$met = &$val;
	}
}
