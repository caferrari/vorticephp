<?php

class Env {
	private static $vars;

	public function setup(){
		self::$vars = &$_SERVER;
		
		$tmproot = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
		$tmproot = preg_replace('@/core/$@', '/', $tmproot);
		
		if (file_exists($tmproot . 'app/config.php')) include tmproot . 'app/config.php';
		
		if (!defined('root'))  define ('root', $tmproot);
		if (!defined('virtualroot')) define ('virtualroot', preg_replace('@/+@', '/', preg_replace('@' . $_SERVER['DOCUMENT_ROOT'] . '|core/core.php@', '/', $_SERVER['SCRIPT_FILENAME'])));
		if (!defined('webroot')) define ('webroot', root . 'app/webroot');
		
		if (!file_exists(root . 'environment')) $env = 'production';
		else $env = file_get_contents(root . 'environment');

		if (preg_match('@^([a-z0-9]+)@', $env, $mat))
			define ('environment', $mat[0]);
			
		define ('production', environment=='production');
		if (!defined('default_controller'))	define ('default_controller', 'index');
		if (!defined('default_action')) 	define ('default_action', 'index');
		if (!defined('default_lang')) 		define ('default_lang', 'pt-br');
		if (!defined('apphash')) 			define ('apphash', md5(__FILE__));
		
		define ('windows', preg_match('@^[a-zA-Z]:@', __FILE__));
		define ('ajax', isset($_SERVER['HTTP_X_REQUESTED_WITH']));
		define ('post', $_SERVER['REQUEST_METHOD'] === 'POST');
		define ('mobile', is_mobile());
		define ('bot', is_bot());

		Env::set('apphash', apphash);
		Env::set('post', post);
		Env::set('windows', windows);
		Env::set('ajax', ajax);
		Env::set('mobile', mobile);
		Env::set('bot', bot);
		Env::set('root', root);
		Env::set('virtualroot', virtualroot);
		Env::set('webroot', webroot);
		Env::set('environment', environment);
		Env::set('production', production);
	}
	
	public function set($index, $val){
		self::$vars[$index] = $val;
	}
	
	public function &get($index){
		return self::$vars[$index];
	}
	
	public static function isPost(){
		return self::$vars['post'];
	}
}
