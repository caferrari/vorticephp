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
	* @param	string	$met method that will be stored
	* @param	string 	$val
	* @access	public
	*/
	public function __set($met, $val){
		if (!preg_match ('@^(pars|_view|_template|_format)$@', $met))
			Response::add($val, $met);
		$this->$met = $val;
	}

	/**
	* Retrieve the object property or a DAO data
	*
	* @param	string	$met method that will be retrieved	
	* @access	public
	* @return   string
	*/
	public function __get($met){
		if (isset($this->$met)) return $this->$met;
		return Response::get($met);	
	}

	/**
	* Set controler propertyes
	*
	* @param	string	$met property that will be created
	* @param	string	$val value of the property
	* @access	public
	* @return   void
	*/
	public function _setvar ($met, $val){
		$this->$met = '';
		$this->$met = &$val;
	}
}
