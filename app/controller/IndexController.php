<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Sample of a framework controller /index or just /
 * @package SampleApp
 * @subpackage Controller
 */
class IndexController{

	/**
	* Execute the action: /index/index or just /
	*
	* @return	void
	*/
	public function index(){
		Vortice::setVar('area', 'Selecione uma Opção');
		Vortice::setVar('desc', '');
	}
}
