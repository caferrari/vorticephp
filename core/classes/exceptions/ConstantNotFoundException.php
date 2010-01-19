<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * ConstantNotFoundException Class, create an error object
 *
 * @version	1
 * @package	Exceptions
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class ConstantNotFoundException extends VorticeException{
	/**
	* Constructor, create an error object
	*
	* @param	string	$desc	Error description
	* @return	void
	*/
	public function __construct($desc=''){
		parent::__construct('Constant not found', $desc, "500");
	}
}
