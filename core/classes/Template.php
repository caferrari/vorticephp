<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Template Class, template engine and framework super core
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 * @author	Luan Almeida <luanlmd@gmail.com>
 */
class Template{
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
	* Is the template engine initialized?
	* @staticvar	boolean
	* @access		private
	*/
	private static $iniciado = false;
	
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
	private static $titulo = "";
	
	/**
	* Forget the template renderization?
	* @staticvar	bool
	* @access		private
	*/
	private static $semtemplate = false;
	
	/**
	* Loaded html content
	* @staticvar	string
	* @access		protected
	*/
	protected static $conteudo = '';
	
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
		if (!self::$iniciado){ 
			self::$iniciado = true;
			self::$titulo = tpl_title;
			self::$rootsite = rootvirtual;
			self::setVar("titulo", self::$titulo);
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
	private function loadTemplates(){
		$dir = rootfisico . "templates";
		if (is_dir($dir) && $dh = opendir($dir))
			while (($file = readdir($dh)) !== false)
				if (ereg("^[0-9a-z\_]+$", $file) && is_dir("$dir/$file") && file_exists("$dir/$file/template.php")) self::addTemplate($file);
	}

	/**
	* Alias to semTemplate Method
	* @return	void
	*/
	public static function noTemplate(){
		self::semTemplate();
	}
	
	/**
	* Disable the template rendering
	* @return	void
	*/
	public function semTemplate(){
		self::$semtemplate = true;
	}

	/**
	* Alias to setTitulo Method
	* @param	string	$titulo	System title
	* @return	void
	*/
	public static function setTitle($titulo){
		self::setTitulo($titulo);
	}
	
	/**
	* Set the system title
	* @param	string	$titulo	System title
	* @return	void
	*/
	public function setTitulo($titulo){
		if ($titulo) self::$titulo = $titulo;
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
		if (!file_exists(rootfisico . "templates/$nome/template.php")) throw(new TemplateNotFoundException($nome));
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
	protected function loadCssDir($dir){
		$tmp = array("mobile" => array(), "screen" => array(), "print" => array());
		if (is_dir($dir)){
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					$nome = explode(".", $file);
					if (count($nome) > 1 && $nome[count($nome)-1] == 'css'){
						if (strstr($nome[0], "mobile"))
							$tmp["mobile"][] = "<link href=\"" . rootvirtual . "$dir/$file\" rel=\"stylesheet\" media=\"all\" />";
						else
							if (strstr($nome[0], "print"))
								$tmp["print"][] = "<link href=\"" . rootvirtual . "$dir/$file\" rel=\"stylesheet\" media=\"print\" />";
							else
								$tmp["screen"][] = "<link href=\"" . rootvirtual . "$dir/$file\" rel=\"stylesheet\" media=\"screen\" />";
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
	protected function makeCss(){
		return self::geraCss();
	}
	
	/**
	* Read and make all Link for the template css's
	* @return	void
	*/
	protected function geraCss(){
		$pasta = self::$tpl;
		$tmp = self::loadCssDir("css");
		$tmp .= self::loadCssDir("templates/$pasta/css");
		return $tmp;
	}
	
	/**
	* Alias for geraJs method
	* @return	void
	*/
	protected function makeJs(){
		return self::geraJs();
	}
	
	/**
	* Read and make all Link for the template scripts
	* @return	void
	*/
	protected function geraJs(){
		$pasta = self::$tpl;
		$tmp = array();
		if (is_dir(rootfisico . "js"))
			if ($handle = opendir(rootfisico . "js")){
				while (false !== ($file = readdir($handle))) {
					$nome = explode(".", $file);
					if (count($nome) > 1 && $nome[count($nome)-1] == 'js') $tmp[] = "<script type=\"text/javascript\" src=\"" . self::$rootsite . "js/$file\"></script>";
				}
				closedir($handle);
			}
		
		if (is_dir("templates/$pasta/js"))
			if ($handle = opendir("templates/$pasta/js")){
				while (false !== ($file = readdir($handle))) {
					$nome = explode(".", $file);
					if (count($nome) == 2 && $nome[1] == 'js') $tmp[] = "<script type=\"text/javascript\" src=\"" . self::$rootsite . "templates/$pasta/js/$file\"></script>";
				}
				closedir($handle);
			}
		sort($tmp);
		return implode("\r\n\t", $tmp);
	}
	
	/**
	* Run users plugins
	* @return	void
	*/
	protected function runPlugins(){
		if (is_dir("app/plugins"))
			if ($handle = opendir("app/plugins")){
				while (false !== ($file = readdir($handle))) {
					$nome = explode(".", $file);
					if (count($nome) == 2 && $nome[1] == 'php'){
						require_once "app/plugins/$file";
						$classe = ucfirst($nome[0]);
						new $classe();
					}
				}
				closedir($handle);
			}
	}
	
	/**
	* Merge the vars to the conteudo
	* @return	void
	*/
	protected function mergeVars(){
		foreach (self::$vars as $k => $v){
			self::$conteudo = str_replace("<!--{$k}-->", $v, self::$conteudo);
			unset(self::$vars[$k]);
		}
	}
	
	/**
	* Execute everything and render the response
	* @return	string
	*/
	public static function render(){
		$meio = Template::executar(false, action, controller, module);

		if (!self::$tpl)  throw(new NoTemplatesLoadedException());
		$pasta = self::$tpl;
		
		ob_start();	
		
		if (ajax){
			if (post){
				header('Content-type: application/json; charset=UTF-8');
				$json = Json::getInstance();
				return ($json->render());
			}
			self::$rendermode = "content";
		}elseif (!self::$semtemplate){
			if (mobile && file_exists(rootfisico . "templates/{$pasta}/mobile.php"))
				include rootfisico . "templates/{$pasta}/mobile.php";
			else 
				include rootfisico . "templates/{$pasta}/template.php";
			self::setVar("csstags", self::geraCss());
			self::setVar("jstags", self::geraJs());
		}
		
		I18n::translate($meio);
		
		self::$conteudo = ob_get_clean();
		self::$conteudo = self::$semtemplate ? $meio : str_replace("<!--conteudo-->", $meio, self::$conteudo);
		self::setVar("rootsite", self::$rootsite);		
		
		self::mergeVars();
		self::runPlugins();
		
		I18n::translate(self::$conteudo);
		
		if (self::$clean){
			self::$conteudo = ereg_replace("[[:space:]]{1,}", " ", self::$conteudo);
			self::$conteudo = ereg_replace("[ ]+</", "</", self::$conteudo);
			//$template = ereg_replace("> <", "><", $template);
		}
		
		if (function_exists("render_" . self::$rendermode)){
			$tmp = "render_" . self::$rendermode;
			$tmp();
			return '';
		}

		switch (self::$rendermode){
			case "html":
					return self::$conteudo;
				break;
			case "json":
					header('Content-type: application/json');
					return json_encode(DAO::getAll());
				break;
			case "serial":
					return serialize(DAO::getAll());
				break;
			case "content":
					return $meio;
				break;
			default:
				throw (new ResponseTypeNotFoundException("Formato de retorno inválido: " . self::$rendermode));
		}
	}
	
	/**
	* Alias for executar method
	* @param	string	$view
	* @param	string	$action
	* @param	string	$controller	
	* @param	string	$module
	* @return	string
	*/
	public static function execute($view, $action, $controller, $module=false){
		return self::executar($view, $action, $controller, $module);
	}
	
	/**
	* Execute a request
	* @param	string	$view
	* @param	string	$action
	* @param	string	$controller	
	* @param	string	$module
	* @return	string
	*/
	public function executar($view, $action, $controller, $module=false){
		if (!self::$masterload && file_exists("app/controller/MasterController.php") && class_exists("MasterController")){
			$obj = new MasterController();
			$t = self::$tpl;
			if (method_exists($obj, "index")) $obj->index();
			if (method_exists($obj, $t)) $obj->$t();
			self::$masterload = true;
		}
		
		$c = $controller;
		$controller = camelize($controller)."Controller";
		
		if (class_exists($controller))
		{
			$obj = new $controller();
			$action = lcfirst(camelize($action));
			if (method_exists($obj, $action)) $obj->$action();
			else throw (new ActionNotFoundException("$controller:$action"));
		}
		else throw (new ControllerNotFoundException($controller));
		
		$_ref = DAO::getAll();
		foreach ($_ref as $k => &$v)
			$$k = $v;
		unset($_ref);
			
		ob_start();
				
		if (mobile){
			$v = $view ? "$c/mobile.$view" : self::getView("mobile.");
			if (file_exists(rootfisico . "app/view/$v.php")){
				include rootfisico . "app/view/$v.php";
				return ob_get_clean();
			}
		}
		
		$v = $view ? "$c/$view" : self::getView();
		$vpath = rootfisico . (module ? "app/modules/" . module . "/view/$v.php" : "app/view/$v.php");
		
		if (file_exists($vpath)){
			include $vpath;
			return ob_get_clean();
		}
	}
}
