<?php

class Dispatcher{

	private $fw;
	private $master_loaded;
	private $view = '';
	
	public function __construct($fw){
		$this->fw = $fw;
		$this->master_loaded = false;
		
		require_once 'Controller.php';
		require_once 'Session.php';
		require_once 'Response.php';
		require_once 'DTO.php';
	}
	
	private function &load_pars($uri){
		preg_match_all('@(([a-z0-9\-\_]+):([^/]*))@', $uri, $match, PREG_SET_ORDER);
		$pars = array();
		foreach ($match as $m) $pars[$m[2]] = $m[3];
		$_POST = array_merge($pars, $_POST);
		return $_POST;
	}
	
	private function decompose_request($uri){
		$request = array(
			'module' => $this->fw->env->modulepath,
			'controller' => 'index',
			'action' => 'index',
			'view' => 'index:index',
			'template' => '',
			'format' => 'html',
			'pars' => array()
		);
		
		if ($uri === '/'){
			$request['pars'] = $_POST;
			return $request;
		}
		
		if (!preg_match('@^/([a-z0-9\-_]+/)?([a-z0-9\-_]+/)?([a-z0-9\-_]+:[^/]+/)*$@', $uri))
			throw new Exception ('Invalid URI format!');
		
		if (preg_match('@^/([a-z0-9_\-]+)/([a-z0-9_\-]+)/@', $uri, $match)){
			$request['controller'] = $match[1];
			$request['action'] = $match[2];
		}elseif (preg_match('@^/([a-z0-9_\-]+)/@', $uri, $match))
			$request['controller'] = $match[1];
		
		$request['view'] = $request['controller'] . ':' . $request['action'];
		$this->view = &$request['view'];
				
		$request['pars'] = $this->load_pars($uri);
		return $request;
	}
	
	public function execute_uri($uri){
		require_once ('Link.php');
		return $this->execute($this->decompose_request($uri));
	}
	
	public function execute($request){
		ob_start();
		extract($request);
		$class = camelize($controller) . 'Controller';
		$path = "{$module}controller/";
		if (!$this->master_loaded){
			$this->exec_master($path, $pars);
			$this->master_loaded = true;
		}
		$this->exec_controller($path, $class, $request);
		new Response($request);
		$content = ob_get_clean();
		
		if ($request['format'] == 'html'){
			require_once 'Template.php';
			$template = new Template($content, $request['template']);
		}
		return $template->execute();
	}
	
	private function exec_master($path, $pars){
		$file = $path . 'MasterController.php';
		if (file_exists($file)){
			require_once ($file);
			if (class_exists('MasterController')){
				$obj = new MasterController();
				$obj->pars = $pars;
				if (method_exists($obj, 'app')) $obj->app();
			}else throw new Exception ('MasterController not found in the MasterController file: '. $file);
		}
	}
	
	private function exec_controller($path, $class, &$request){
		$file = $path . $class . '.php';
		if (file_exists($file)){
			require_once ($file);
			if (class_exists($class)){
				$action = &$request['action'];
				$obj = new $class();
				$obj->_setvar('pars', &$request['pars']);
				$obj->_setvar('_view', &$request['view']);
				$obj->_setvar('_format', &$request['format']);
				$action2 = $action . '_' . $_SERVER['REQUEST_METHOD'];
				if (!method_exists($obj, $action) &&  !method_exists($obj, $action2)) throw new Exception ($class . '->' . $action . ' not found in the class ' . $class);
				if (method_exists($obj, $action)) $obj->$action();
				if (method_exists($obj, $action2)) $obj->$action2();
			}else
				throw new Exception ('Class ' . $class . ' not found in the file: '. $file);
		}else 
			if ($class !== 'MasterController') throw new Exception ('Controller file not found: '. $file);
	}
	
	public function set_view($view){
		$this->view = $view;
	}

}
