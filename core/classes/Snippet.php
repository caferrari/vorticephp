<?php
/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>
 * All rights reserved. 
 */

/**
 * Framework core class
 *
 * @version	1
 * @package	Utils
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Snippet{
	/**
	* Constructor, insert a code snippet in any place where it's called
	*
	* @return	void
	* @private
	*/
	public function __construct($path){
		$vpath = root . "app/view/_snippets/" . $path . ".php";
		if (file_exists($vpath)) include ($vpath);
	}
}
