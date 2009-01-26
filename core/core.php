<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
* Framework start file
* @package Framework
*/

require_once "funcoes.php";
try{
	print (new Core());
}catch (Exception $e){
	print ($e);
}
