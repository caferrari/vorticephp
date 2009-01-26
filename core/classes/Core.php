<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Framework core class
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Core{

	/**
	* Return content
	*
	* @var		string
	* @access	private
	*/
	private $content;

	/**
	* Constructor. make all happens
	*
	* @return	void
	*/
	public function __construct(){
		error_reporting(E_ALL);
		header('Content-type: text/html; charset=UTF-8');

		define ("ajax", isset($_SERVER["HTTP_X_REQUESTED_WITH"]));
		define ("post", $_SERVER["REQUEST_METHOD"] == "POST");

		require_once rootfisico . "app/config.php";
		require_once rootfisico . "app/funcoes.php";

		if (!defined("rootvirtual")) define ("rootvirtual", str_replace($_SERVER["DOCUMENT_ROOT"], "/", rootfisico));
		if (!defined("default_lang")) define("default_lang", "pt-br");
		Link::translate_uri();

		if (file_exists(rootfisico . "app/route.php")) include rootfisico . "app/route.php";

		I18n::start();

		if (!defined("controller")) Link::trataQuery();
		
		Post::start();
		Template::start();
		include rootfisico . "app/app.php";
		$this->content = Template::render();
	}
	
	/**
	* Return loaded contents
	*
	* @return	string
	*/
	public function &__toString(){
		return $this->content;
	}
}
