<?php

class Error extends Exception{
	public function handler($code, $string, $file, $line){
		$e = new self($string, $code);
		$e->line = $line;
		$e->file = $file;
		ob_end_clean();
		throw $e;
	}

	public function find_controller(){
		$fw = Vortice::get_fw();

		$file = false;
		if (file_exists($fw->env->modulepath . 'controller/ErrorController.php')) $file = $fw->env->modulepath . 'controller/ErrorController.php';
		elseif(file_exists($fw->env->apppath . 'controller/ErrorController.php')) $file = $fw->env->apppath . 'controller/ErrorController.php';

		if ($file){
			$request = array(
					'module' => $fw->env->modulepath,
					'controller' => 'error',
					'action' => 'handler',
					'view' => '',
					'template' => '',
					'format' => 'html',
					'pars' => array('exception' => $this)
				);
			return $fw->dispatcher->execute($request);
		}
		throw $this;
	}

	public static function setup() {
		error_reporting(-1);
		ini_set('display_errors', true);
		set_error_handler(array(new self(), 'handler'));
	}
}