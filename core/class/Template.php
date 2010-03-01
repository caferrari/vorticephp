<?php

class Template {

	private $content;

	public function __construct($content, $template=''){
		
		$this->content = $content;
		$this->template = $template;
				
		if ($template === ''){
			$this->template = $this->auto_load();
		}
	}
	
	private function auto_load(){
		return 'my_template';
	}
	
	public function execute(){
		$path = Vortice::get_fw()->env->approot . 'templates/' . $this->template . '.php';
		if (!file_exists($path)) 
			throw new Exception ('Template "' . $this->template . '" not found');
			
		extract (Response::getAll());
		
		ob_start();
		include $path;
		$tpl = ob_get_clean();

		$tpl = str_replace("<!--content-->", $this->content, $tpl);
		
		return $tpl;
	}

}
