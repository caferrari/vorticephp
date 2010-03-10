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
		header('X-Powered-By: VorticePHP');
		
		require_once 'Env.php';
		Env::setup();
		
		require_once 'Crypt.php';
		require_once 'Session.php';
		require_once 'Vortice.php';
		require_once 'I18n.php';
		require_once 'Link.php';
		require_once 'Route.php';
		require_once 'Post.php';

		if (file_exists(root . 'app/functions.php')) include root . 'app/functions.php';

		if (file_exists(root . 'app/route.php')) include root . 'app/route.php';

		Link::translateUri();

		I18n::start();

		if (!defined('controller')) Link::parseQuery();
		
		Post::start();
		Vortice::start();
		
		if (file_exists(root . 'app/app.php')) include root . 'app/app.php';
		$this->content = Vortice::render();
		header ('Vortice-LoadTime:' . (microtime_float() - $this->start));
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
