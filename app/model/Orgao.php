<?php
/**
 * Sample of a framework data object
 * @package SampleApp
 * @subpackage Model
 * @subpackage DTO
 */
class Orgao extends DTO{
	/**
	* Contruct a Exemplo object
	*
	* @return	void
	*/
	public function __construct($id=0, $nome='', $sigla=''){
		if (isset($this->id)) return; // PDO BUG	
		$this->id 		= $id;
		$this->nome 	= $nome;
		$this->sigla 	= $sigla;
	}
}
