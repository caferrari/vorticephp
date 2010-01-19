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
class ControllerNotFoundException extends VorticeException{
	/**
	* Constructor, create an error object
	*
	* @param	string	$desc	Error description
	* @return	void
	*/
	public function __construct($desc=''){
		$msg = "<p>Check if the controller name is correct in the url!</p>
				<p>if the name is correct, fix this problem creating the file <strong>" . virtualroot . "app/controller/{$desc}.php</strong></p>
				<pre class=\"code\">&lt;?php\n\n//Paste it inside the controller file\n\nclass $desc extends Controller{\n\n\tpublic function index(){\n\n\t}\n}</pre>";
		parent::__construct("Controller \"$desc\" not Found", $msg, '404');
	}
}
