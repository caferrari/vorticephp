<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Sample of a framework data access object
 * @package SampleApp
 * @subpackage Model
 * @subpackage DTO
 */
class ExemploDAO extends DAO {
	
	/**
	* Insert a object into the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	void
	*/
	public function insert($dto){
		$sql = "INSERT INTO orgaos (sigla, nome)
				VALUES ('{$dto->sigla}', '{$dto->nome}')";
		return Database::getInstance()->exec($sql);
	}
	
	/**
	* Update a object in the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	void
	*/
	public function update($dto){
		$sql = "UPDATE orgaos SET sigla = '{$dto->sigla}', nome = '{$dto->nome}' WHERE id = {$dto->id}";
		return Database::getInstance()->exec($sql);
	}
	
	/**
	* Select a object in the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	Exemplo
	*/
	public function select($id){
		$sql = "SELECT * FROM orgaos WHERE id = $id";
		return Database::getInstance()->queryOne($sql);
	}
	
	/**
	* Delect a object from the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	void
	*/
	public function delete($dto){
		Database::getInstance()->exec("DELETE FROM orgaos WHERE id={$dto->id}");
	}
	
	/**
	* Load all objects from the database
	*
	* @return	array
	*/
	public function getList(){
		$sql = "SELECT o.* FROM orgaos o ORDER BY o.nome";
		return Database::getInstance()->query($sql);
	}
}
?>
