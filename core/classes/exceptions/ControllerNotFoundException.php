<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * ActionNotFoundException Class, create an error object
 *
 * @version	1
 * @package	Exceptions
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class ControllerNotFoundException extends BaseException{
	/**
	* Constructor, create an error object
	*
	* @param	string	$desc	Error description
	* @return	void
	*/
	public function __construct($desc=''){
		$msg = "Check if the controller name is correct in the url!<br />if the name is correct, fix this problem 
				creating the file <strong>" . rootvirtual . "app/controller/{$desc}.php</strong><br />";
		parent::__construct("Controller \"$desc\" not Found", $msg, '404');
	}
}
