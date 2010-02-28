<?php

class Env{

	private $vars;

	public function __construct(){
		$this->vars = array();
	}
	
	public function __get($var){
		return $this->get($var);
	}
	
	public function get($var){
		if (array_key_exists($var, $this->vars)) return $this->vars[$var];
		return false;
	}
	
	public function &set($var, $value){
		$this->vars[$var] = $value;
		return $this->vars[$var];
	}
	
}
