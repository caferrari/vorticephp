<?php
/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Sample of a framework data object
 * @package SampleApp
 * @subpackage Model
 * @subpackage DTO
 */
class Exemplo {

	/**
	* Contruct a Exemplo object
	*
	* @return	void
	*/
	public function __construct($id=0, $nome='', $sigla=''){
		$this->id 		= $id;
		$this->nome 	= $nome;
		$this->sigla 	= $sigla;
	}
}
