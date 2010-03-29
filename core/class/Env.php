<?php
/**
 * Class responsible for the application environment,
 * care of variables as language, the root of the application, among other
 */
class Env{
	/**
	 * @var array $vars variable system
	 */
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
