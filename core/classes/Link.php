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
	* @param	string	$page		format: [model][+controller:[action]]
	* @param	string	$pars		link params
	* @return	void
	*/
	function __construct($page='', $pars=''){
		$this->el = $this->createLink($page, $pars);
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
		$page = $url['url'];
		$page = explode(':', $page);
		$pars = '';
		
		switch (count($page)){
			case 3:
				if ($page[1]==='') $page[1] = default_controller;
				break;
			case 2:
				if ($page[1]==='') $page[1] = '';
				break;
		}
		
		$page = implode($page, '/');

		if (isset($url['pars'])){
			$pars = http_build_query($url['pars']);
			$pars = str_replace('=', ':', $pars);
			$pars = str_replace('&', '/', $pars);
		}
		
		$l = $page . '/' . $pars . '/';
		
		
		
		return preg_replace('@\/+@', '/', $l);
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
		$page = array();
		$partes = explode('/', $url);
		foreach ($partes as $p)
			if (preg_match('@.*:.*@', $p)){
				$p = explode(':', $p); 
				$pars[$p[0]] = $p[1];
			}else
				$page[] = $p;
		$page = implode($page, ':');

		$json = serialize(array('url' => $page, 'pars' => $pars));
		return $json;
	}

	/**
	* Create, Replace or remove parameters from alread defined uri's
	*
	* @param	string	$page		/some/uri/with:pars/
	* @param	string	$pars	    link new params
	* @return	string
	* @static
	*/
	private function replacePars($page, $pars=''){
		$uri = preg_replace("@\/+@", "/", virtualroot . ((request_lang != default_lang) ? request_lang : "") . "/$page/");

		if ($pars==='') return $uri;
		parse_str($pars, $pars);

		foreach ($pars as $p => $v)
			if (preg_match("@/$p:@", $uri))
				$uri = preg_replace("@/$p:[^/]*/@", $v=='' ? '/' : "/$p:$v/", $uri);
			elseif ($v!='') $uri .= "$p:$v/";
		return $uri;
	}

	/**
	* Create a URI
	*
	* @param	string	$page		[model][+controller+[action]]
	* @param	string	$pars	link params
	* @return	string
	* @static
	*/
	static function createLink($page='', $pars=''){
		if (!preg_match("/^([a-z\-]+\+)?([a-z\-]+)(:[a-z\-]+)?$/", $page))
			return self::replacePars($page, $pars);
		$page = preg_split("/[:\+]/", $page);

		if (count($page)==3){
			if ($page[2]==default_action) $page[2] = '';
			if ($page[1]==default_controller && $page[2]=='') unset($page[2]);
			reset_keys($page);
		}
		if (count($page)==2){
			if ($page[0]==default_controller && $page[1]==default_action) $page=array();
			else if ($page[1]==default_action) unset($page[1]);
			reset_keys($page);
		}
		if (count($page)==1){
			if ($page[0] == default_controller) $page = array();
			reset_keys($page);
		}

		parse_str(is_array($pars) ? http_build_query($pars) : $pars, $p);
		if (count($page)==0 || $page[0] == '') $page="";
		else $page = implode(":", $page);
		foreach ($p as $k=>$v)
			if ($v==='') unset($p[$k]);
		$url = $pars ? serialize(array("url" => $page, "pars" => $p)) : serialize(array("url" => $page));
		$link = (function_exists("link_encode")) ? link_encode($url) : Link::default_encode($url);
		if (request_lang != default_lang)
			return virtualroot . request_lang . "/$link";

		return virtualroot . "$link";
	}

	
	/**
	* Translate the requested URI, check for internationalization request and create the uri constant
	*
	* @return	void
	* @static
	*/
	static function translateUri(){
		$q = $_SERVER['REQUEST_URI'];
		
		if (virtualroot !== '/') $q = preg_replace('/^'.addcslashes(virtualroot, '/').'/', '', $q);
		$q = preg_replace('@^\/|\/$@', '', $q);
		$tmp = explode('/', $q);
		if ($tmp[0] !== ''){
			if (preg_match('@^([a-z]{2}|[a-z]{2}-[a-z]{2})$@', $tmp[0]) && !file_exists(root . 'app/controller/' . ucfirst($tmp[0]).'Controller.php') && !is_dir(root . 'app/modules/' . $tmp[0])){
				define('request_lang', $tmp[0]);
				unset($tmp[0]);
				$q = implode('/', $tmp);
				if (request_lang==default_lang){
					header('Location: ' . virtualroot . $q , true);
					exit();
				}
			}else $q = implode('/', $tmp);
		}
		define('uri', urldecode($q));
		if (!defined('request_lang')) define('request_lang', default_lang);
	}
	
	/**
	* Convert the requested uri to global constants (module, controller, action) and usefull parameters
	*
	* @return	void
	* @static
	*/
	static function parseQuery(){
		global $_PAR;
		$q = uri;
		$route = Route::exec();
		if ($route) $q = $route;
		else{
			if (function_exists('link_decode'))
				try {
					$q = unserialize(link_decode($q));
				}catch (Exception $e){
					throw new BaseException('Invalid URL', 'URL checksum failed!', '400');
				}
			else
				$q = unserialize(Link::default_decode($q));
		}
		
		$controller = $action = '';

		$q['url'] = isset($q['url']) ? $q['url'] : '';
		if (isset($q['pars'])) $_PAR = (array)$q['pars'];

		if ($q['url'] !== ''){
			$url = explode(':', $q['url']);
			if (count($url)==2){
				$controller = $url[0];
				$action = $url[1];
			}else
				if ($url[0]{0} === '.') $action = $url[0];
				else $controller = $url[0];
		}

		$tmp = explode('.', $action);
		if (count($tmp) > 1){
			$action = ($tmp[0] === '') ? '' : $tmp[0];
			Vortice::setRenderMode($tmp[1]);
		}else{
			$action = $tmp[0];
			$tmp = explode('.', $controller);
			if (count($tmp) > 1){
				$controller = ($tmp[0] === '') ? '' : $tmp[0];
				Vortice::setRenderMode($tmp[1]);
			}else $controller = $tmp[0];
		}
		
		if (!routed && Vortice::$rendermode==='html'){
			if ($controller===default_controller && ($action===default_action || $action==='')){
				header('Location: ' . new Link(default_controller . ':' . default_action, $_PAR), true, 301);
				exit();
			}
			if ($controller!==default_controller && $action===default_action){
				header('Location: ' . new Link($controller . ':' . default_action, $_PAR), true, 301);
				exit();
			}
		}
		
		if ($action==='') $action = default_action;
		if ($controller==='') $controller = default_controller;

		define ('controller', $controller);
		define ('action', $action);
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
