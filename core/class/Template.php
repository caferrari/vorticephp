<?php

class Template {
	private $content;
	private $template = false;

	public function __construct($content, $template=''){
		$this->content = $content;
		$this->template = $template;
		if ($template === ''){
			$this->template = $this->auto_load();
		}
	}
	
	private function auto_load(){
		$path = Vortice::get_fw()->env->approot . 'templates/';
		if (file_exists($path . 'default.php')) return 'default';
		return '';
	}

	private function merge_env(&$tpl){
		$env = Vortice::get_fw()->env;
		$tpl = str_replace("<!--content-->", $this->content, $tpl);
		$tpl = str_replace("<!--lang-->", $env->lang, $tpl);
	}

	private function load_files($dir, $ext){
		$itens = array();
		try {
			$d = new DirectoryIterator($dir);
			$regex = '@\.' . $ext . '$@';
			foreach ($d as $a){
				if ($a->getType()==='file' && preg_match($regex, $a->getFilename())){
					$itens[] = virtualroot . str_replace(root, '', $a->getPathname()) . '?' . $a->getMTime();
				}
			}
		}catch (Exception $e){ }
		sort($itens);
		return $itens;
	}
	
	private function load_css(){
		if ($this->template){
			$global_css = $this->load_files(Vortice::get_fw()->env->approot . 'webroot/css/' , 'css');
			$tpl_css = $this->load_files(Vortice::get_fw()->env->approot . 'webroot/templates/' . $this->template . '/css/' , 'css');
			$css = array_merge($global_css, $tpl_css);
			foreach ($css as &$c)
				$c = '<link href="' . $c . '" rel="stylesheet" media="screen" />';
			return implode ("\n\t", $css);
		}
	}

	private function load_js(){
		if ($this->template){
			$global = $this->load_files(Vortice::get_fw()->env->approot . 'webroot/js/' , 'js');
			$tpl = $this->load_files(Vortice::get_fw()->env->approot . 'webroot/templates/' . $this->template . '/js/' , 'js');
			$js = array_merge($global, $tpl);
			foreach ($js as &$j)
				$j = '<script type="text/javascript" src="' . $j . '"></script>';
			return implode ("\n\t", $js);
		}
	}

	public function execute(){
		if ($this->template == '') return $this->content;
	
		$path = Vortice::get_fw()->env->approot . 'templates/' . $this->template . '.php';
		if (!file_exists($path)) 
			throw new VorticeException ('Template "' . $this->template . '" not found', 500);
			
		extract (Response::getAll());

		ob_start();
		include $path;
		$tpl = ob_get_clean();

		$this->merge_env($tpl);

		$tpl = str_replace("<!--csstags-->", $this->load_css(), $tpl);
		$tpl = str_replace("<!--jstags-->", $this->load_js(), $tpl);

		return $tpl;
	}

}
