<?php

class I18n{

	private $fw;

	public function check_lang($lang){
		$method = 'check_lang_' . $this->fw->env->i18n_format;
		if (method_exists($this, $method))
			return $this->$method($lang);
		throw new Exception ('We dont support "' . $lang . '" lang format yet');
	}
	
	private function check_lang_conf($lang){
		return file_exists($this->fw->env->modulepath . 'i18n/' . $lang . '.conf');
	}
	
	public function __construct($fw){
		$this->fw = $fw;
	}

}
