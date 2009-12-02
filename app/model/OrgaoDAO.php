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
class OrgaoDAO extends DAO {
	
	/**
	* Select a object in the database
	*
	* @param	Exemplo	$dto	Record object
	* @return	Exemplo
	*/
	public function get($id){
		$sql = "SELECT * FROM orgao WHERE id=$id";
		return Database::getInstance()->queryOne($sql);
	}
	
	/**
	* Load all objects from the database
	*
	* @return	array
	*/
	public function getList(){
		$sql = "SELECT o.* FROM orgao o ORDER BY o.nome";
		return Database::getInstance()->query($sql);
	}
}
