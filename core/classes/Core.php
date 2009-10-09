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
	* Start Execution Time
	*
	* @var		float
	* @access	private
	*/
	public $start;

	/**
	* Constructor. make all happens
	*
	* @return	void
	*/
	public function __construct(){
		error_reporting(E_ALL);
		$this->start = microtime_float();
		header('Content-type: text/html; charset=UTF-8');
		
		define ("windows", preg_match("/^[a-zA-Z]:/", __FILE__));
		define ("ajax", isset($_SERVER["HTTP_X_REQUESTED_WITH"]));
		define ("post", $_SERVER["REQUEST_METHOD"] == "POST");
		define ("mobile", is_mobile());
		define ("bot", is_bot());
		
		require_once "Crypt.php";
		require_once "Session.php";
		require_once "Template.php";
		require_once "I18n.php";
		require_once "Link.php";
		require_once "Route.php";
		require_once "Post.php";

		if (!defined("rootfisico"))  define ("rootfisico", str_replace("core/classes/Core.php", "", str_replace("\\", "/", __FILE__)));
		if (!defined("rootvirtual")) define ("rootvirtual", preg_replace("/\/+/", "/", str_replace(str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]), "/", rootfisico)));
		
		if (file_exists(rootfisico . "app/config.php")) include rootfisico . "app/config.php";

		if (!defined("default_controller")) define ("default_controller", "index");
		if (!defined("default_action")) 	define ("default_action", "index");
		if (!defined("default_lang")) 		define ("default_lang", "pt-br");
		if (!defined("tpl_title")) 			define ("tpl_title", md5(__FILE__));
		
		if (file_exists(rootfisico . "app/funcoes.php")) include rootfisico . "app/funcoes.php";

		if (!defined("default_lang")) define("default_lang", "pt-br");

		Link::translate_uri();

		if (file_exists(rootfisico . "app/route.php")) include rootfisico . "app/route.php";

		I18n::start();

		if (!defined("controller")) Link::trataQuery();
		
		Post::start();
		Template::start();
		include rootfisico . "app/app.php";
		$this->content = Template::render();
		header ("Vortice-LoadTime:" . (microtime_float() - $this->start));
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
