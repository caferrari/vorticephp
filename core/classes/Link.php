<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Link class, Work with uri encode and decode
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Link{

	/**
	* Temporary link encoded
	*
	* @var		string
	* @access	private
	*/
	private $el = '';
	
	/**
	* Constructor, Create a URI
	*
	* @param	string	$pagina		format: [model][+controller:[action]]
	* @param	string	$parametros	link params
	* @return	void
	*/
	function __construct($pagina='', $parametros=''){
		$this->el = $this->criaLink($pagina, $parametros);
	}
	
	/**
	* Encode a link
	*
	* @param	array	$url		array with url data
	* @return	string
	* @static
	*/
	static function default_encode($url){
		$url = unserialize($url);
		$pagina = $url["url"];
		$pagina = explode(":", $pagina);
		$pars = '';
		
		switch (count($pagina)){
			case 3:
				if ($pagina[1]=='') $pagina[1] = default_controller;
				break;
			case 2:
				if ($pagina[1]=='') $pagina[1] = "";
				break;
		}
		
		$pagina = implode($pagina, "/");
		
		if (isset($url['pars'])){
			$pars = http_build_query($url["pars"]);
			$pars = str_replace("=", ":", $pars);
			$pars = str_replace("&", "/", $pars);
		}
		
		$l = $pagina . "/" . $pars . "/";
		return preg_replace("@\/+@", "/", $l);
	}
	
	/**
	* Decode a link
	*
	* @param	string	$url		encoded uri
	* @return	string
	* @static
	*/
	static function default_decode($url){
		$pars = array();
		$pagina = array();
		$partes = explode("/", $url);
		foreach ($partes as $p)
			if (preg_match("/.*:.*/", $p)){
				$p = explode(":", $p); 
				$pars[$p[0]] = $p[1];
			}else
				$pagina[] = $p;
		$pagina = implode($pagina, ":");

		$json = serialize(array("url" => $pagina, "pars" => $pars));
		return $json;
	}
	
	/**
	* Create a URI
	*
	* @param	string	$pagina		[model][+controller+[action]]
	* @param	string	$parametros	link params
	* @return	string
	* @static
	*/
	static function criaLink($pagina='', $parametros=''){
		if (!preg_match("/^([a-z\-]+\+)?([a-z\-]+)(:[a-z\-]+)?$/", $pagina))
			return preg_replace("@\/+@", "/", rootvirtual . ((request_lang != default_lang) ? request_lang : "") . "/$pagina/");
		$pagina = preg_split("/[:\+]/", $pagina);

		if (count($pagina)==3){
			if ($pagina[2]==default_action) $pagina[2] = '';
			if ($pagina[1]==default_controller && $pagina[2]=='') unset($pagina[2]);
			reset_keys($pagina);
		}
		if (count($pagina)==2){
			if ($pagina[0]==default_controller && $pagina[1]==default_action) $pagina=array();
			else if ($pagina[1]==default_action) unset($pagina[1]);
			reset_keys($pagina);
		}
		if (count($pagina)==1){
			if ($pagina[0] == default_controller) $pagina = array();
			reset_keys($pagina);
		}

		parse_str(is_array($parametros) ? http_build_query($parametros) : $parametros, $p);
		if (count($pagina)==0 || $pagina[0] == '') $pagina="";
		else $pagina = implode(":", $pagina);
		$url = $parametros ? serialize(array("url" => $pagina, "pars" => $p)) : serialize(array("url" => $pagina));
		$link = (function_exists("link_encode")) ? link_encode($url) : Link::default_encode($url);
		if (request_lang != default_lang)
			return rootvirtual . request_lang . "/$link";
		
		return rootvirtual . "$link";
	}
	
	/**
	* Translate the requested URI, check for internationalization request and create the uri constant
	*
	* @return	void
	* @static
	*/
	static function translate_uri(){
		$q = $_SERVER["REQUEST_URI"];
		
		if (!ajax && !post && !preg_match("@\/$@", $q)){
			header ("HTTP/1.1 301 Moved Permanently");
			header ("Location: $q/");
		}
		
		if (rootvirtual != "/") $q = preg_replace("/^".addcslashes(rootvirtual, "/")."/", "", $q);
		$q = preg_replace("/^\/|\/$/", "", $q);
		$tmp = explode("/", $q);
		if ($tmp[0] != ''){
			if (preg_match("/^([a-z]{2}|[a-z]{2}-[a-z]{2})$/", $tmp[0]) && !file_exists(rootfisico . "app/controller/" . ucfirst($tmp[0])."Controller.php") && !is_dir(rootfisico . "app/modules/{$tmp[0]}")){
				define("request_lang", $tmp[0]);
				unset($tmp[0]);
				$q = implode("/", $tmp);
				if (request_lang==default_lang){
					header("Location: " . rootvirtual . $q , true, 301);
					exit();
				}
			}else $q = implode("/", $tmp);
		}
		define("uri", urldecode($q));
		if (!defined("request_lang")) define("request_lang", default_lang);
	}
	
	/**
	* Convert the requested uri to global constants (module, controller, action) and usefull parameters
	*
	* @return	void
	* @static
	*/
	static function trataQuery(){
		global $_PAR;
		$q = uri;
		$route = Route::exec();
		if ($route) $q = $route;
		else{
			if (function_exists("link_decode"))
				try {
					$q = unserialize(link_decode($q));
				}catch (Exception $e){
					throw new BaseException("Invalid URL", "URL checksum failed!", '400');
				}
			else
				$q = unserialize(Link::default_decode($q));
		}
		
		$module = false;
		$controller = '';
		$action = '';

		$q["url"] = isset($q["url"]) ? $q["url"] : '';

		if ($q["url"] != ''){
			$url = explode("+", $q["url"]);
			if (count($url)==2){
				$module = $url[0];
				$url = $url[1];
			}else $url = $url[0];
		
			$url = explode(":", $url);
			
			if (count($url)==2){
				$controller = $url[0];
				$action = $url[1];
			}else{
				if (is_dir(rootfisico . "app/modules/" . $url[0]))
					$module = $url[0];
				elseif ($url[0]{0} == ".")
					$action = $url[0];
				else
					$controller = $url[0];
			}
		}

		$tmp = explode(".", $action);
		if (count($tmp) > 1){
			$action = ($tmp[0] == '') ? '' : $tmp[0];
			Template::setRenderMode($tmp[1]);
		}else{
			$action = $tmp[0];
			$tmp = explode(".", $controller);
			if (count($tmp) > 1){
				$controller = ($tmp[0] == '') ? '' : $tmp[0];
				Template::setRenderMode($tmp[1]);
			}else
				$controller = $tmp[0];
		}
		
		if (!routed && Template::$rendermode=='html'){
			$tmpmod = '';
			if ($module != false) $tmpmod = "$module+";
			if ($controller==default_controller && ($action==default_action || $action=='')){
				header("Location: " . new Link($tmpmod . default_controller . ":" . default_action, $_PAR), true, 301);
				exit();
			}
			if ($controller!=default_controller && $action==default_action){
				header("Location: " . new Link("{$tmpmod}{$controller}:" . default_action, $_PAR), true, 301);
				exit();
			}
		}
		
		if ($action=='') $action = default_action;
		if ($controller=='') $controller = default_controller;

		define ("module", $module);
		define ("controller", $controller);
		define ("action", $action);
		
		if (isset($q["pars"]))
			if (is_object($q["pars"]) || is_array($q["pars"])) foreach ($q["pars"] as $k => $p) $_PAR[$k] = $p;
	}
	
	/**
	* Return the encoded uri.
	*
	* @return	string
	*/
	public function __toString(){
        return $this->el;
    }
}
