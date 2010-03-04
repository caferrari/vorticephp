<?php

class Route{

	private static $routes = array();

	public static function add($er, $action, $pars=''){
	
		self::$routes[] = array($er, $action, $pars);
	
	}
	
	public static function exec($uri){
		$env = Vortice::get_fw()->env;

		$routes = $env->modulepath . 'route.php';
		if (!file_exists($routes)) return false;
		include_once($routes);
		$uri = preg_replace('@^/@', '', $uri);
		foreach (self::$routes as $r){
				
			if (preg_match('@' . $r[0] . '@', $uri, $match)){
				$p = $r[2];
				for ($x=1; $x<count($match); $x++) $p = str_replace('%' . $x, $match[$x], $p);
				$p = preg_replace('@%[0-9]+@', '', $p);
				parse_str($p, $pars);

				$pars = array_merge($pars, $_POST);
				$_POST = &$pars;

				define ('routed', true);
				
				list ($controller, $action) = explode (':', $r[1]);

				return array(
					'module' => $env->modulepath,
					'controller' => $controller,
					'action' => $action,
					'view' => $action,
					'template' => '',
					'format' => 'html',
					'pars' => &$_POST
				);
			}
		
		}
		
		return false;
		
	}

}
