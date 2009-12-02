<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * QueryErrorException Class, create an error object
 *
 * @version	1
 * @package	Exceptions
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class QueryErrorException extends BaseException{
	/**
	* Constructor, create an error object
	*
	* @param	string	$desc	Error description
	* @return	void
	*/
	public function __construct($desc=''){
		parent::__construct('Error executing the query', $desc, "500");
	}
}
?>
