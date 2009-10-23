<?php
/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
* Framework start file
* @package Framework
*/

// Enable error reporting
error_reporting(E_ALL);

require_once "classes/Core.php";
require_once "funcoes.php";
try{
	print (new Core());
}catch (Exception $e){
	print ($e);
}
