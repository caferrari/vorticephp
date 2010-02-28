<?php

class Dispatcher{

	private $fw;
	private $master_loaded;

	public function __construct($fw){
		$this->fw = $fw;
		$this->executed = array();
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
		}elseif (preg_match('@^/([a-z0-9_\-]+)/@', $uri, $match)){
			$request['controller'] = $match[1];
		}
		
		$request['pars'] = $this->load_pars($uri);
		return $request;
	}
	
	public function execute_uri($uri){
		$this->execute($this->decompose_request($uri));
	}
	
	public function execute($request){
		extract($request);
		$class = camelize($controller) . 'Controller';
		$path = "{$module}controller/";
		$this->exec_controller($path, 'MasterController', 'index', $pars);
		$this->exec_controller($path, $class, $action, $pars);
	}
	
	private function exec_controller($path, $class, $action, &$pars){
		$file = $path . $class . '.php';
		if (file_exists($file)){
			require_once ($file);
			if (class_exists($class)){
				$obj = new $class();
				$action2 = $action . '_' . $_SERVER['REQUEST_METHOD'];
				if (!method_exists($obj, $action) &&  !method_exists($obj, $action2)) throw new Exception ($class . '->' . $action . ' not found in the class ' . $class);
					
				if (method_exists($obj, $action)) $obj->$action();
				if (method_exists($obj, $action2)) $obj->$action2();
					
			}else
				throw new Exception ('Class ' . $class . ' not found in the file: '. $file);
		}else 
			if ($class !== 'MasterController') throw new Exception ('Controller file not found: '. $file);
	}

}
