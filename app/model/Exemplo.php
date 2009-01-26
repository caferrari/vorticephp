<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Sample of a framework data object
 * @package SampleApp
 * @subpackage Model
 * @subpackage DTO
 */
class Exemplo extends DTO {
	/**
	* Record field id
	* @var	int
	*/
	public $id;
	
	/**
	* Record field name
	* @var	string
	*/
	public $nome;
	
	/**
	* Record field acronym
	* @var	string
	*/
	public $sigla;

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
?>
