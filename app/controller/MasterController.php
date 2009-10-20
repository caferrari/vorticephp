<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Sample of a framework template controller
 * @package SampleApp
 * @subpackage Controller
 */
class MasterController{
	
	/**
	* Front controller.... everytime executed
	*
	* @return	void
	*/
	function __construct(){
		Database::getInstance()->init("mysql.ferrari.eti.br", "exemplobd", "exemplobd", "exemplodb");
		// if u want to conect in another Database...
		//Banco::getInstance('another_one')->inicializa("database2_ip", "database2_user", "database2_pass", "database2_name", BD_PGSQL);
		Template::setVar("mensagem", Post::renderMsg());
		//Template::setClean(); // Clean the whitespaces before sending the page.. 
	}
	
	
	/**
	* Executed when "nome_template" template is executed
	*
	* @return	void
	*/
	function nome_template(){

	}
}
