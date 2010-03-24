<?php

class Vortice {

	private static $fw;
	private $template = false;
	private $content = '';

	public function __construct() {
		require_once('VorticeException.php');
		VorticeException::setup();
		require_once 'Dispatcher.php';
		$this->dispatcher = new Dispatcher($this);

		try{
			$this->load_method();

			require_once 'functions.php';
			self::$fw = $this;

			require_once 'Env.php';
			$this->env = new Env();

			require_once 'I18n.php';
			$this->i18n = new I18n($this);
			$this->env->set('default_lang', 'pt-br');
			$this->env->set('i18n_format', 'conf');
			$this->load_patch($_SERVER);
			$this->load_module_and_lang('/' . $this->env->uri);

			define ('virtualroot', $this->env->vroot);
			define ('root', $this->env->root);
			define ('uri', preg_replace('@^/@', '', $this->env->uri));
			define ('request_lang', $this->env->lang);
			//define ('environment', $this->env->environment);
			
			require_once ('Route.php');
			$route = Route::exec($this->env->uri);
			
			$this->env->set('routed', routed);

			require_once ('Link.php');

			if (!$route){
				$this->validate_uri();
				$this->content = $this->dispatcher->execute_uri($this->env->uri);
			}else
				$this->content = $this->dispatcher->execute($route);
		}catch (VorticeException $e){
			$this->content = $e->find_controller();
		}

		$this->etag();
	}

	private function etag(){
		/*
		$hash = 'Vortice-' . md5($this->content);
		header('Etag: ' . $hash);
		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH']==$hash){
			$this->content = '';
			set_header(304);
		}
		*/
	}

	private function validate_uri() {
		if (!valid_uri($_SERVER['REQUEST_URI']))
			if ($_SERVER['REQUEST_METHOD'] === 'GET')
				redirect($_SERVER['REQUEST_URI'] . '/');
			else
				throw new VorticeException ('The uri must end with a slash (/)', 403);
	}

	private function load_method() {
		if (isset($_POST['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
			$server = strtoupper($_SERVER['REQUEST_METHOD']);
			if (preg_match('@(HEAD|POST|PUT|DELETE)@', strtoupper($_POST['REQUEST_METHOD'])))
				$_SERVER['REQUEST_METHOD'] = $_POST['REQUEST_METHOD'];
			else
				throw new VorticeException ('No support to "' . $_POST['REQUEST_METHOD'] . '" HTTP Method');
		}
	}

	private function load_patch(&$server) {
		$this->env->set('vroot', str_replace('core/handler.php', '', $server['SCRIPT_NAME']));
		$this->env->set('root', str_replace('core/handler.php', '', $server['SCRIPT_FILENAME']));
		$this->env->set('uri', str_replace($this->env->vroot, '', $server['REQUEST_URI']));
	}

	private function load_module_and_lang($uri) {
		$parts = decompose_uri($uri);
		if (isset($parts[0]) && is_dir($this->env->root . 'app/modules/' . $parts[0])) {
			$this->env->set('module', $parts[0]);
			$this->env->set('modulepath', $this->env->root . 'app/modules/' . $parts[0] . '/');
			$this->env->set('approot', $this->env->root . $parts[0] . '/');
			$r = array_shift($parts);
			$uri = preg_replace("@/($r)\b@", '', $uri, 1);
		}else {
			$this->env->set('module', 'default');
			$this->env->set('modulepath', $this->env->root . 'app/');
			$this->env->set('approot', $this->env->root . 'app/');
		}

		if (isset($parts[0]) && $this->i18n->check_lang($parts[0])) {
			$this->env->set('lang', $parts[0]);
			$r = array_shift($parts);
			$uri = preg_replace("@/($r)\b@", '', $uri, 1);
		}else
			$this->env->set('lang', 'pt-br');

		$uri = preg_replace('@/+@', '/', $uri);

		$this->env->set('uri', $uri);
	}

	public static function &get_fw() {
		return self::$fw;
	}

	public static function setView($view) {
		self::$fw->dispatcher->set_view($view);
	}

	public static function setTemplate($name) {
		self::$fw->template = $name;
	}

	public function __toString() {
		return $this->content;
	}

}
