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
		$this->start = microtime_float();
		header('Content-type: text/html; charset=UTF-8');
		header("X-Powered-By: VorticePHP");
		
		$tmproot = str_replace("\\", "/", dirname($_SERVER["SCRIPT_FILENAME"]) . "/");
		$tmproot = preg_replace("@/core/$@", "/", $tmproot);
		
		if (file_exists("{$tmproot}app/config.php")) include "{$tmproot}app/config.php";
		
		if (!defined('root'))  define ('root', $tmproot);
		if (!defined('virtualroot')) define ('virtualroot', preg_replace("@/+@", "/", preg_replace("@{$_SERVER["DOCUMENT_ROOT"]}|core/core.php@", "/", $_SERVER["SCRIPT_FILENAME"])));
		if (!defined('webroot')) define ('webroot', root . 'app/webroot');
		
		if (!file_exists(root . 'environment')) $env = 'production';
		else $env = file_get_contents(root . 'environment');

		if (preg_match('@^([a-z0-9]+)@', $env, $mat))
			define ('environment', $mat[0]);
			
		define ('production', environment=='production');
		
		require_once "Crypt.php";
		require_once "Session.php";
		require_once "Vortice.php";
		require_once "I18n.php";
		require_once "Link.php";
		require_once "Route.php";
		require_once "Post.php";

		if (!defined("default_controller")) define ("default_controller", "index");
		if (!defined("default_action")) 	define ("default_action", "index");
		if (!defined("default_lang")) 		define ("default_lang", "pt-br");
		if (!defined("apphash")) 			define ("apphash", md5(__FILE__));
		
		define ("windows", preg_match("/^[a-zA-Z]:/", __FILE__));
		define ("ajax", isset($_SERVER["HTTP_X_REQUESTED_WITH"]));
		define ("post", $_SERVER["REQUEST_METHOD"] == "POST");
		define ("mobile", is_mobile());
		define ("bot", is_bot());
		
		if (file_exists(root . "app/functions.php")) include root . "app/functions.php";

		if (!defined("default_lang")) define("default_lang", "pt-br");

		Link::translateUri();

		if (file_exists(root . "app/route.php")) include root . "app/route.php";

		I18n::start();

		if (!defined("controller")) Link::parseQuery();
		
		Post::start();
		Vortice::start();
		
		if (file_exists(root . "app/app.php")) include root . "app/app.php";
		$this->content = Vortice::render();
		header ("Vortice-LoadTime:" . (microtime_float() - $this->start));
	}
	
	/**
	* Return loaded contents
	*
	* @return	string
	*/
	public function &__toString(){
		if (!is_string($this->content)) $this->content = '';
		return $this->content;
	}
}
