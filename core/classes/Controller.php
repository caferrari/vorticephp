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
	* @access	private
	*/
	function __set($met, $val){
		DAO::add($val, $met);
	}
}
