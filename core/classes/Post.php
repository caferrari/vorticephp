<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Post class, Work with Form Posted data
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Post
{
	/**
	* Errors array
	*
	* @staticvar	array
	* @access		private
	*/
	private static $erros = array();
	
	/**
	* Form fields array
	*
	* @staticvar	array
	* @access		private
	*/
	private static $form = array();
	
	/**
	* Error/Sucess message
	*
	* @staticvar	string
	* @access		private
	*/
	public static $mensagem = '';
	
	/**
	* Message type
	*
	* @staticvar	int
	* @access	private
	*/
	public static $tipo;
	
	/**
	* Constructor
	* @return	void
	*/
	private function __construct(){
		throw (new Exception("Don't do that!!"));
	}

	/**
	* Load sucess or form data and error message if exists
	*
	* @return	void
	*/
	public function start(){
		define ("POST_ERRO", 0);
		define ("POST_OK", 1);
		global $_PAR;
		if (!is_array($_PAR)) $_PAR = array();
		foreach (array_merge($_POST, $_GET, $_PAR) as $k => $v)
			if (ereg("^id", $k) && ($v !='' && !is_numeric($v))) throw new IntegerRequiredException("it can be a SQL injection try!");
		
		self::$form = array();
		
		if (Session::get("form_val")){
			$tmp = unserialize(Session::get("form_val"));
			self::$form = is_array($tmp) ? $tmp : array();
		}
	
		$tmp = @unserialize(Session::get('form_erros'));
		self::$erros = (is_array($tmp) && count($tmp) > 0) ? $tmp : "";
		
		if (Session::get("form_mensagem")){
			self::$mensagem = Session::get('form_mensagem');
			self::$tipo = Session::get('form_tipo');
		}
		
		foreach ($_POST as $k => $v) self::setVal($k, $v);
		
		Session::del('form_erros');
		Session::del("form_val");
		Session::del('form_tipo');
		Session::del('form_mensagem');
		Session::set("form_val", serialize($_POST));
	}
	
	/**
	* Return the message type
	*
	* @return	int
	*/
	public function getTipo(){
		return self::$tipo;
	}

	/**
	* Return errors
	*
	* @return	array
	*/
	public function getErros(){
		return self::$erros;
	}
	
	/**
	* Return form field value
	*
	* @param	string	$c		Form field name
	* @return	string
	*/
	public function getVal($c){
		if (isset(self::$form[$c])) return self::$form[$c];
	}
	
	/**
	* Load a object as post data
	*
	* @return	void
	*/
	public function load($obj, $prefix='')
	{
		if (is_object($obj)) foreach (get_object_vars($obj) as $c => $v) self::setVal(@$prefix[$c] . $c, $v);
	}
	
	/**
	* Set form field value
	*
	* @param	string	$c		Form field name
	* @param	string	$v		Form field value
	* @return	void
	*/
	public function setVal($c,$v)
	{
		self::$form[$c] = $v;
	}
	
	/**
	* Render message to html
	*
	* @return	string
	*/
	public function renderMsg(){
		$tmp = '';
		switch (self::$tipo){
			case POST_OK:
					$tmp = "<div id=\"mensagem\" class=\"ok\">";
					$tmp .= "<p>" . self::$mensagem . "</p>";
					$tmp .= "</div>";
				break;
			case POST_ERRO:
					if (strlen(self::$mensagem)==0) return;
					if (!is_array(self::$erros)) self::$erros = array();
					$tmp = "<div id=\"mensagem\" class=\"erro\">";
					$tmp .= "<p>" . self::$mensagem . "</p>";
					$tmp .= "<ul>";
					foreach (self::$erros as $erro)
						$tmp .= is_array($erro) ? "<li>{$erro[1]}" : "<li>$erro</li>";
					$tmp .= "</ul>";
					$tmp .= "</div>";
				break;
			default:
				return '';
		}
		return $tmp;
	}
	
	/**
	* Put validation errors on a session and redirect to previews page
	*
	* @param	string	$mensagem	Errors message
	* @param	array	$erros		Errors array
	* @return	void
	*/
	public function setErros($mensagem, $erros=''){
		if ($erros=='') $erros = array();
		if (!is_array($erros)) throw (new ArrayRequiredException($erros));
		
		foreach($erros as $k => $v) is_array($erros[$k]) ? $erros[$k][1] = e($v[1]) : $erros[$k] = e($v);
		$mensagem = e($mensagem);
		
		if (ajax){
			$json = Json::getInstance();
			$json->set(0, $mensagem);
			$json->addDAO("errors", $erros);
			foreach(DAO::getAll() as $k => $d)
				$json->addDAO($k, $d);
		}else{
			Session::set('form_erros', serialize($erros));
			Session::set('form_tipo', POST_ERRO);
			Session::set('form_mensagem' , $mensagem);
			die ("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=" . $_SERVER['HTTP_REFERER'] . "\"></head><body></body></html>");
		}
	}
	
	/**
	* Sucess post
	*
	* @param	string	$mensagem	Sucess message
	* @param	string	$redirec	Redirect URL encoded with Link class
	* @return	void
	*/
	public function setSucesso($mensagem, $redirect=false){
		$mensagem = e($mensagem);
	
		if (ajax){
			$json = Json::getInstance();
			foreach(DAO::getAll() as $k => $d)
				$json->addDAO($k, $d);
			$json->set(1, $mensagem);
		}else{
			Session::set('form_tipo', POST_OK);
			Session::set('form_mensagem' , $mensagem);
		}
		if ($redirect) redirect($redirect);
	}
	
	/**
	* Create a object based on the posted data and the current controller
	*
	* @param	string	$class 		Optional class name, if not given the atual controller will be used as class
	* @return	object (DTO)
	*/
	public function &makeObject($class=''){
		if ($class == '') $class = ucfirst(controller);
		if (class_exists($class)){
			$obj = new $class;
			foreach (get_object_vars($obj) as $k => $v) $obj->$k = p($k);
			return $obj;
		}
		throw new ModelNotFoundException("Modelo $class não encontrado");
	}

}
