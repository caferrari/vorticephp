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
class ActionNotFoundException extends BaseException{
	/**
	* Constructor, create an error object
	*
	* @param	string	$desc	Error description
	* @return	void
	*/
	public function __construct($desc=''){
		$msg = "Check if the action name is correct ino the controller!<br />if the name is correct, fix this problem 
				creating the file method $desc into the controller" . rootvirtual . "app/controller/{$desc}.php</strong><br />";
		parent::__construct("Action \"$desc\" not Found", $msg, '404');
	}
}
