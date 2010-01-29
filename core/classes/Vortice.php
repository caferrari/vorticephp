<?php
/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Vortice core Class, template engine and framework super core
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 * @author	Luan Almeida <luanlmd@gmail.com>
 */
class Vortice{
	/**
	* Variables to replace on the template render
	* @staticvar	array
	* @access		private
	*/
	private static $vars = array();
	
	/**
	* Templates loaded
	* @staticvar	array
	* @access		private
	*/
	private static $templates = array();
	
	/**
	* Flag to check if template controller was alread executed
	* @staticvar	bool
	* @access		private
	*/
	private static $masterload = false;
	
	/**
	* Template setted to render the page
	* @staticvar	string
	* @access		private
	*/
	private static $tpl = '';
	
	/**
	* Setted view to render the content generated on controller
	* @staticvar	string
	* @access		private
	*/
	private static $view = '';
	
	/**
	* Is the framework engine initialized?
	* @staticvar	boolean
	* @access		private
	*/
	private static $started = false;
	
	/**
	* Clean html white spaces before send it to the browser?
	* @staticvar	bool
	* @access		private
	*/
	private static $clean = false;
	
	/**
	* Aplication base uri
	* @staticvar	string
	* @access		private
	*/
	private static $rootsite = "/";
	
	/**
	* Aplication title
	* @vstaticar	array
	* @access		private
	*/
	private static $title = "";
	
	/**
	* Forget the template renderization?
	* @staticvar	bool
	* @access		private
	*/
	private static $notemplate = false;
	
	/**
	* Loaded html content
	* @staticvar	string
	* @access		protected
	*/
	protected static $contents = '';
	
	/**
	* Return data format
	* @staticvar	string
	* @static
	*/
	public static $rendermode = 'html';

	/**
	* Constructor
	* @return	void
	*/
	private function __construct(){
		throw (new Exception("Don't do that!!"));
	}

	/**
	* Start the template engine
	* @return	void
	*/
	public static function start(){
		if (!self::$started){ 
			self::$started = true;
			self::$title = apphash;
			self::$rootsite = virtualroot;
			self::setVar("title", self::$title);
			self::setView(controller . ":" . action);
			self::loadTemplates();
			if (!defined("module")) define ("module", false);
			ob_start();
		}
	}

	/**
	* Auto load the templates templates
	* @return	void
	* @access	private
	*/
	private static function loadTemplates(){
		$dir = root . "app/webroot/templates";
		if (is_dir($dir) && $dh = opendir($dir))
			while (($file = readdir($dh)) !== false)
				if (preg_match("/^[0-9a-z\_]+$/", $file) && is_dir("$dir/$file") && file_exists("$dir/$file/template.php")) self::addTemplate($file);
	}

	/**
	* Disable the template rendering
	* @return	void
	*/
	public static function disableTemplate(){
		self::$notemplate = true;
	}
	
	/**
	* Set the render mode
	* @param	string	$mode	new render mode
	* @return	void
	*/
	public static function setRenderMode($mode){
		self::$rendermode = $mode;
	}

	/**
	* Manual add a template
	* @param	string	$nome	template name
	* @return	void
	*/
	public static function addTemplate($nome){
		if (!file_exists(root . "app/webroot/templates/$nome/template.php")) throw(new TemplateNotFoundException($nome));
		self::$templates[$nome] = $nome;
		if (self::$tpl == '') self::$tpl = $nome;
	}
	
	/**
	* Set the response to be cleaned or not
	* @param	bool	$op		Clear the whitespaces before send to the browser?
	* @return	void
	*/
	public static function setClean($op = true){
		self::$clean = $op;
	}
	
	/**
	* Set internal var to be replaced on the template
	* @param	string	$nome	name
	* @param	string	$valor	value
	* @return	void
	*/
	public static function setVar($nome, $valor){
		self::$vars[$nome] = e($valor);
	}
	
	/**
	* Set the active template
	* @param	string	$nome	template name
	* @return	void
	*/
	public static function setTemplate($nome){
		if (!isset(self::$templates[$nome])) throw (new TemplateNotLoadedException($nome));
		self::$tpl = $nome;
	}
	
	/**
	* Set the active controller name
	* @param	string	$nome	controller name
	* @return	void
	*/
	public static function setController($nome){
		self::$controller = $nome;
	}
	
	/**
	* Set the active action
	* @param	string	$nome	action name
	* @return	void
	*/
	public static function setAction($action){
		self::$action = $action;
	}
	
	/**
	* get the requested view
	* @return	string
	*/
	public static function getView($prefix=''){
		if (strstr(self::$view, ":") !== false){
			list($c, $v) = explode(":", self::$view);
			return "$c/{$prefix}{$v}";
		}
		return controller . "/{$prefix}" . self::$view;
	}
	
	/**
	* Set the view
	* @param	string	$nome	view name
	* @return	void
	*/
	public static function setView($nome){
		self::$view = $nome;
	}
	

	/**
	* Load the CSS files inside a dir
	* @param	string	$dir	path
	* @return	string
	*/	
	protected static function loadCssDir($dir){
		$tmp = array("mobile" => array(), "screen" => array(), "print" => array());
		if (is_dir(root . "app/webroot/$dir")){
			if ($handle = opendir(root . "app/webroot/$dir")) {
				while (false !== ($file = readdir($handle))) {
					$nome = explode(".", $file);
					if (count($nome) > 1 && $nome[count($nome)-1] == 'css'){
						$mtime = filemtime(root . "app/webroot/$dir/$file");
						if (strstr($nome[0], "mobile"))
							$tmp["mobile"][] = "<link href=\"" . virtualroot . "$dir/$file?$mtime\" rel=\"stylesheet\" media=\"all\" />";
						else
							if (strstr($nome[0], "print"))
								$tmp["print"][] = "<link href=\"" . virtualroot . "$dir/$file?$mtime\" rel=\"stylesheet\" media=\"print\" />";
							else
								$tmp["screen"][] = "<link href=\"" . virtualroot . "$dir/$file?$mtime\" rel=\"stylesheet\" media=\"screen\" />";
					}
				}
				closedir($handle);
			}
		}
		if (mobile && count($tmp["mobile"]) > 0){
			sort($tmp["mobile"]);
			return implode("\r\n\t", $tmp["mobile"]);
		}
		sort($tmp["screen"]);
		return implode("\r\n\t", array_merge($tmp["screen"], $tmp["print"]));
	}
	
	/**
	* Alias for geraCss method
	* @return	void
	*/
	protected static function makeCss(){
		return self::geraCss();
	}
	
	/**
	* Read and make all Link for the template css's
	* @return	void
	*/
	protected static function geraCss(){
		$pasta = self::$tpl;
		$tmp = self::loadCssDir("css");
		$tmp .= self::loadCssDir("templates/$pasta/css");
		return $tmp;
	}
	
	/**
	* Alias for geraJs method
	* @return	void
	*/
	protected static function makeJs(){
		return self::geraJs();
	}
	
	/**
	* Load the JS files inside a dir
	* @param	string	$dir	path
	* @return	string
	*/	
	protected static function loadJsDir($dir){
		$tmp = array();
		if (is_dir(root . "app/webroot/$dir"))
			if ($handle = opendir(root . "app/webroot/$dir")){
				while (false !== ($file = readdir($handle))) {
					$mtime = filemtime(root . "app/webroot/$dir/$file");
					if (preg_match("/\.js$/", $file)) $tmp[] = "<script type=\"text/javascript\" src=\"" . self::$rootsite . "$dir/$file?$mtime\"></script>";
				}
				closedir($handle);
			}
		sort($tmp);
		return implode("\r\n\t", $tmp);
	}
	
	/**
	* Read and make all Link for the template scripts
	* @return	void
	*/
	protected static function geraJs(){
		$pasta = self::$tpl;
		$tmp = self::loadJsDir("js");
		$tmp .= self::loadJsDir("templates/$pasta/js");
		return $tmp;
	}
	
	/**
	* Merge the vars to the contents
	* @return	void
	*/
	protected static function mergeVars(){
		foreach (self::$vars as $k => $v){
			self::$contents = str_replace("<!--{$k}-->", $v, self::$contents);
			unset(self::$vars[$k]);
		}
	}
	
	/**
	* Execute everything and render the response
	* @return	string
	*/
	public static function render(){
		$middle = self::execute(false, action, controller, module);

		if (!self::$tpl)  self::$semtemplate = true;
		else $pasta = self::$tpl;
		
		ob_start();	
		
		if (ajax){
			if (post){
				header('Content-type: application/json; charset=UTF-8');
				$json = Json::getInstance();
				return ($json->render());
			}
			self::$rendermode = "content";
		}elseif (!self::$notemplate){
			if (mobile && file_exists(root . "app/webroot/templates/{$pasta}/mobile.php"))
				include root . "app/webroot/templates/{$pasta}/mobile.php";
			else 
				include root . "app/webroot/templates/{$pasta}/template.php";
			self::setVar("csstags", self::geraCss());
			self::setVar("jstags", self::geraJs());
		}
		
		I18n::translate($middle);
		
		self::$contents = ob_get_clean();
		self::$contents = self::$notemplate ? $middle : preg_replace("@<!--conte(udo|nt)-->@", $middle, self::$contents);
		self::setVar("rootsite", self::$rootsite);		
		
		self::mergeVars();
		
		I18n::translate(self::$contents);
		
		if (self::$clean){
			self::$contents = preg_replace("/[[:space:]]{1,}/", " ", self::$contents);
			self::$contents = preg_replace("/[ ]+<\//", "</", self::$contents);
		}

		switch (self::$rendermode){
			case "html":
				return self::$contents;
			case "json":
				//header('Content-type: application/json');
				header('Content-type: text/plain');
				return json_encode(DAO::getAll());
			case "serial":
				header('Content-type: text/plain');
				return serialize(DAO::getAll());
			case "content":
				return $middle;
			default:
				$tmp_render = "render_" . self::$rendermode;
				if (function_exists($tmp_render)) return $tmp_render();
				throw (new ResponseTypeNotFoundException("Formato de retorno inválido: " . self::$rendermode));
		}
	}
	
	/**
	* Alias for execute method
	* @param	string	$view
	* @param	string	$action
	* @param	string	$controller	
	* @param	string	$module
	* @return	string
	*/
	public static function executar($view, $action, $controller, $module=false){
		return self::execute($view, $action, $controller, $module);
	}
	
	/**
	* Execute a request
	* @param	string	$view
	* @param	string	$action
	* @param	string	$controller	
	* @param	string	$module
	* @return	string
	*/
	public static function execute($view, $action, $controller, $module=false){
		ob_start();
		
		if (!self::$masterload && class_exists("MasterController")){
			$obj = new MasterController();
			self::$masterload = true;
		}
		
		$c = $controller;
		$controller = camelize($controller)."Controller";
		if (class_exists($controller)){
			$obj = new $controller();
			$action = lcfirst(camelize($action));
			if (method_exists($obj, $action)) $obj->$action();
			else throw (new ActionNotFoundException("$controller->$action()"));
		}else{
			// if its a static view
			$vpath = array(
				root . 'app/view/_static/' . (uri ? uri : 'index') . '.' . request_lang . '.php',
				root . 'app/view/_static/' . uri . '/index.' . request_lang . '.php',
				root . 'app/view/_static/' . (uri ? uri : 'index') . '.php',
				root . 'app/view/_static/' . uri . '/index.php'
			);
			foreach ($vpath as $vp)
				if (file_exists($vp)){
					include ($vp);
					return ob_get_clean();
				}
			throw (new ControllerNotFoundException($controller));
		} 
		
		$_ref = DAO::getAll();
		foreach ($_ref as $k => &$v)
			$$k = $v;
		unset($_ref);
				
		if (mobile){
			$v = $view ? "$c/mobile.$view" : self::getView("mobile.");
			if (file_exists(root . "app/view/$v.php")){
				include root . "app/view/$v.php";
				return ob_get_clean();
			}
		}
		
		$v = $view ? "$c/$view" : self::getView();
		$vpath = root . (module ? "app/modules/" . module . "/view/$v.php" : "app/view/$v.php");
		
		if (file_exists($vpath)){
			include $vpath;
			return ob_get_clean();
		}
	}
}
