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
	private static $errors = array();
	
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
	* @access		public
	*/
	public static $message = '';
	
	/**
	* Message type
	*
	* @staticvar	int
	* @access	public
	*/
	public static $type;
	
	/**
	* Have data sent back via post error?
	*
	* @staticvar	bool
	* @access	public
	*/
	public static $hasData = false;
	
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
	public static function start(){
		define ("POST_ERROR", 0);
		define ("POST_OK", 1);
		global $_PAR;
		if (!is_array($_PAR)) $_PAR = array();
		foreach (array_merge($_POST, $_GET, $_PAR) as $k => $v)
			if ($v!='' && ($k=="id" || preg_match("@^id[_\-]@", $k)) && !is_numeric($v)) throw new VorticeException("Integer Required", "Any parameter started with 'id' must be an Integer", '403');
		self::$form = array();
		
		$tmp = unserialize(Session::get("form_val"));
		self::$form = is_array($tmp) ? $tmp : array();
		if (count($tmp) != '')
			self::$hasData = true;
			
		$tmp = @unserialize(Session::get('form_errors'));
		self::$errors = (is_array($tmp) && count($tmp) > 0) ? $tmp : "";
		
		if (Session::get("form_message"))
		{
			self::$message = Session::get('form_message');
			self::$type = Session::get('form_type');
		}
		
		foreach ($_POST as $k => $v) self::setVal($k, $v);
		
		Session::del('form_errors');
		Session::del("form_val");
		Session::del('form_type');
		Session::del('form_message');
		Session::set("form_val", serialize($_POST));
	}
	
	/**
	* Return the message type
	*
	* @return	int
	*/
	public static function getType(){
		return self::$type;
	}

	/**
	* Return errors
	*
	* @return	array
	*/
	public static function getErrors(){
		return self::$errors;
	}
	
	/**
	* Return errors
	*
	* @return	array
	*/
	public static function getError($field=''){
		if (isset(self::$errors[$field])) return self::$errors[$field];
		return false;
	}
	
	/**
	* Return form field value
	*
	* @param	string	$c		Form field name
	* @return	string
	*/
	public static function getVal($c){
		$v = stripslashes((isset(self::$form[$c])) ? self::$form[$c] : '');
		return str_replace(
			array("\""),
			array("&quot;"),
			$v			
		);
	}
	
	/**
	* DTO object factory
	*
	* @return	dto
	*/
	public static function toObject($class = '')
	{
		if ($class == '') $class = ucfirst(controller);
		if (!class_exists($class)) $class = 'DTO';		
		$obj = new $class();
		if (!is_array($_POST)) return false;
		foreach ($_POST as $k => $v) $obj->$k = p($k);
		return $obj;
	}
	
	/**
	* Inject posted data into an existing object
	*
	* @return	dto
	*/
	public static function intoObject($obj)
	{
		if (!is_array($_POST)) return $obj;
		foreach ($_POST as $k => $v) $obj->$k = p($k);
		return $obj;
	}
	
	/**
	* Load a object as post data if dont have data sent back by a post error
	*
	* @return	void
	*/
	public static function load($obj, $prefix='')
	{
		if (self::$hasData) return;
		if (is_object($obj)) foreach (get_object_vars($obj) as $c => $v) self::setVal((isset($prefix[$c]) ? $prefix[$c] : '') . $c, stripslashes($v));
	}
	
	/**
	* Load a object as post data forced!
	*
	* @return	void
	*/
	public static function forceLoad($obj, $prefix='')
	{
		if (is_object($obj)) foreach (get_object_vars($obj) as $c => $v) self::setVal((isset($prefix[$c]) ? $prefix[$c] : '') . $c, stripslashes($v));
	}
	
	/**
	* Set form field value
	*
	* @param	string	$c		Form field name
	* @param	string	$v		Form field value
	* @return	void
	*/
	public static function setVal($c,$v)
	{
		self::$form[$c] = $v;
	}
	
	/**
	* Render message to html
	*
	* @return	string
	*/
	public static function render(){
		$tmp = '';
		switch (self::$type){
			case POST_OK:
					$tmp = "<div id=\"message\" class=\"ok\">";
					$tmp .= "<p>" . self::$message . "</p>";
					$tmp .= "</div>";
				break;
			case POST_ERROR:
					if (strlen(self::$message)==0) return;
					if (!is_array(self::$errors)) self::$errors = array();
					$tmp = "<div id=\"message\" class=\"error\">";
					$tmp .= "<p>" . self::$message . "</p>";
					$tmp .= "<ul>";
					foreach (self::$errors as $error)
						$tmp .= is_array($error) ? "<li>{$error[1]}" : "<li>$error</li>";
					$tmp .= "</ul>";
					$tmp .= "</div>";
				break;
			default:
				return '';
		}
		return $tmp;
	}
	
	/**
	* Auto render error/success messages to <!--message--> html comment
	*
	* @return	void
	*/
	public static function autoRender()
	{
		Vortice::setVar("message", self::render());
	}
	
	/**
	* Put validation errors on a session and redirect to previews page
	*
	* @param	string	$message	Errors message
	* @param	array	$erros		Errors array
	* @return	void
	*/
	public static function error($message, $errors=''){
		if ($errors=='') $errors = array();
		if (!is_array($errors)) throw (new ArrayRequiredException($errors));
		
		foreach($errors as $k => $v) is_array($errors[$k]) ? $errors[$k][1] = e($v[1]) : $errors[$k] = e($v);
		$message = e($message);
		
		if (ajax || !isset($_SERVER['HTTP_REFERER']) || Vortice::$rendermode == 'json'){
			$tmp = array();
			foreach ($errors as $k => $v)
				$tmp[] = array("key" => $k, "value" => $v);
			$json = Json::getInstance();
			$json->set(0, $message, $tmp);
			foreach(DAO::getAll() as $k => $d)
				$json->addPackage($k, $d);
			if (ajax) header('Content-type: text/json');
			exit($json->render());
		}else{
			Session::set('form_errors', serialize($errors));
			Session::set('form_type', POST_ERROR);
			Session::set('form_message' , $message);
			exit ("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=" . $_SERVER['HTTP_REFERER'] . "\"></head><body></body></html>");
		}
	}
	
	/**
	* Sucess post
	*
	* @param	string	$message	Sucess message
	* @param	string	$redirec	Redirect URL encoded with Link class
	* @return	void
	*/
	public static function success($message, $redirect=false){
		$message = e($message);
	
		if (ajax || Vortice::$rendermode=='json'){
			$json = Json::getInstance();
			foreach(DAO::getAll() as $k => $d)
				$json->addPackage($k, $d);
			$json->set(1, $message);
			if (ajax) header('Content-type: text/json');
			if (!$redirect) exit($json->render());
		}else{
			Session::set('form_type', POST_OK);
			Session::set('form_message' , $message);
		}
		if ($redirect) redirect($redirect);
	}
	
	/**
	* Create a object based on the posted data and the current controller
	*
	* @param	string	$class 		Optional class name, if not given the atual controller will be used as class
	* @return	object (DTO)
	*/
	public static function &makeObject($class=''){
		if ($class == '') $class = ucfirst(controller);
		if (class_exists($class)){
			$obj = new $class;
			foreach (get_object_vars($obj) as $k => $v) $obj->$k = p($k);
			return $obj;
		}
		throw new ModelNotFoundException("Modelo $class não encontrado");
	}
}
