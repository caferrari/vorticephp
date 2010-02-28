<?php

class Vortice{

	private static $fw;

	private $template = false;
	private $view = '';
	

	public function __construct(){
		$this->load_method();
		
		require_once 'functions.php';
		$this->validate_uri();
		
		self::$fw = $this;
		
		require_once 'Env.php';
		$this->env = new Env();
		
		require_once 'I18n.php';
		$this->i18n = new I18n($this);
		$this->env->set('default_lang', 'pt-br');
		$this->env->set('i18n_format', 'conf');
		$this->load_patch($_SERVER);
		$this->load_module_and_lang($this->env->uri);
		
		require_once 'Dispatcher.php';
		$this->dispatcher = new Dispatcher($this);
		$this->dispatcher->execute_uri($this->env->uri);
		
	}

	private function load_method(){
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_set($_POST['REQUEST_METHOD'])){
			$server = strtoupper($_SERVER['REQUEST_METHOD']);
			if (preg_match('@(HEAD|POST|PUT|DELETE)@', strtoupper($_POST['REQUEST_METHOD'])))
				$_SERVER['REQUEST_METHOD'] = $_POST['REQUEST_METHOD'];
			else
				throw new Exception ('No support to "' . $_POST['REQUEST_METHOD'] . '" HTTP Method');
		}
	}

	private function validate_uri(){
		if (!valid_uri($_SERVER['REQUEST_URI']))
			if ($_SERVER['REQUEST_METHOD'] == 'GET')
				redirect($_SERVER['REQUEST_URI'] . '/');
			else
				throw new Exception ('The uri must end with a slash (/)');
	}

	private function load_patch(&$server){
		$this->env->set('vroot', str_replace('core/handler.php', '', $server['SCRIPT_NAME']));
		$this->env->set('root', str_replace('core/handler.php', '', $server['SCRIPT_FILENAME']));
		$this->env->set('uri', str_replace($this->env->vroot, '', $server['REQUEST_URI']));
	}

	private function load_module_and_lang($uri){
		$parts = decompose_uri($uri);
		if (isset($parts[0]) && is_dir($this->env->root . 'app/modules/' . $parts[0])){
			$this->env->set('module', $parts[0]);
			$this->env->set('modulepath', $this->env->root . 'app/modules/' . $parts[0] . '/');
			$this->env->set('approot', $this->env->root . $parts[0] . '/');
			array_shift($parts);
		}else{
			$this->env->set('module', 'default');
			$this->env->set('modulepath', $this->env->root . 'app/');
			$this->env->set('approot', $this->env->root . 'app/');
		}
				
		if (isset($parts[0]) && $this->i18n->check_lang($parts[0])){
			$this->env->set('lang', $parts[0]);
			array_shift($parts);
		}else 
			$this->env->set('lang', 'pt-br');
		
		$this->env->set('uri', compose_uri($parts));
	}
	
	public static function setView ($view){
		self::$fw->view = $view;
	}
	
	public static function setTemplate($name){
		self::$fw->template = $name;
	}

}
