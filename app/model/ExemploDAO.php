<?php
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
				VALUES (?, ?)";
		$args = array($dto->sigla, $dto->nome);
		return Database::getInstance()->exec($sql, $args);
	}
	
	/**
	* Update a object in the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	void
	*/
	public function update($dto){
		$sql = "UPDATE orgaos SET sigla=?, nome=? WHERE id=?";
		$args = array($dto->sigla, $dto->nome, $dto->id);
		return Database::getInstance()->exec($sql, $args);
	}
	
	/**
	* Select a object in the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	Exemplo
	*/
	public function get($id){
		$sql = "SELECT * FROM orgaos WHERE id=$id";
		return Database::getInstance()->queryOne($sql);
	}
	
	/**
	* Delect a object from the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	void
	*/
	public function delete($id){
		$args = array($id);
		Database::getInstance()->exec("DELETE FROM orgaos WHERE id=?", $args);
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
