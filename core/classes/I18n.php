<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */


/**
 * I18n class, Internacionalization controller class
 *
 * @version	1
 * @package	Framework
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class I18n{

	/**
	* Phrases translations conteiner
	*
	* @staticvar	array
	* @access		private
	* @private
	*/
	private static $dic = array();

	/**
	* Load a translation file
	*
	* @param	string	$file	file path
	* @return	void
	* @access	private
	*/
	private function load_conf($file){
		$ar = self::$dic;
		
		$content = explode("\n", @file_get_contents(rootfisico . $file));
		$key = false;
		foreach($content as $i){
			$i = trim($i);
			if ($i != '' && $i{0} != '#'){
				if (!$key) $key = $i;
				else{
					$ar[md5($key)] = $i;
					$key = false;
				}
			}
		}
		self::$dic = $ar;
	}

	/**
	* Load all available translations
	*
	* @param	string	$dir	configs directory
	* @param	string	$nick	translation nickname
	* @return	array
	* @access	private
	*/
	private function load_lang($dir, $nick){
		$t = array(default_lang);
		if (is_dir($dir)){
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false)
				    if (ereg("([a-z\-]+)\.conf$", $file, $mat)) $t[] = $mat[1];
				closedir($dh);
			}
		}
		return $t;
	}

	/**
	* Load all browser requested languages
	*
	* @param	string	$file	file path
	* @return	void
	* @access	private
	*/
	public function get_lang(){
		if (!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) return array(default_lang);
		preg_match_all("/([a-z\-]{2,})+,?;?/", strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]), $langs);
		$langs = $langs[1];
		
		foreach ($langs as $l)
			if (ereg("([a-z]{2})-[a-z]{2}", $l, $mat))
				$langs[] = $mat[1];
				
		return $langs;
	}

	/**
	* Start the internacionalization engine
	*
	* @param	string	$module	app module name
	* @return	void
	*/
	function start($module = '_base'){
		if (!defined("default_lang")) throw (new ConstantNotFoundException("Please make a constant called 'default_lang' on the file app/config.php"));

		$av_lang = self::load_lang(rootfisico . "app/i18n", "_base");

		if (count($av_lang) > 1){
			$langs = $av_lang;		
			if (is_array(self::get_lang()) && is_array($av_lang))
				$langs = array_unique(array_intersect(self::get_lang(), $av_lang));
			if (count($langs) > 0){
				$tmp = array_shift($langs);
				if ($tmp!=default_lang && Session::get("defined_lang")==''){
					Session::set("defined_lang", $tmp);
					header("Location: " . rootvirtual . "$tmp/" . uri , true, 301) and exit();
				}
			}
		}

		if (!in_array(request_lang, $av_lang)) throw (new TranslationNotFoundException(request_lang));
		$active = request_lang;
		if (strstr($active, "-")){
			$tmp = explode("-", $active);
			self::load_conf("app/i18n/{$tmp[0]}.conf");
			if ($module != "_base")
				self::load_conf("app/modules/$module/i18n/{$tmp[0]}.conf");
		}
		
		self::load_conf("app/i18n/{$active}.conf");
		if ($module != "_base")
			self::load_conf("app/modules/$module/i18n/{$active}.conf");
	}
	
	/**
	* Tranlate a phrase or return the same if translation not found
	*
	* @param	string	$s		phrase to be translated and some variables to be inserted into
	* @param	mixed	$v,...	values to put into $s string
	* @return	string
	*/
	public function e() {
		$args = func_get_args();
		if (count($args)>0 && is_array($args[0])) $args = $args[0];
		
		$str = $args[0];
		unset($args[0]);
		
		$str = isset(self::$dic[md5($str)]) ? self::$dic[md5($str)] : $str; 
		
		foreach ($args as $k => $v)
			$str = str_replace("%$k", $v, $str);
		return $str;
	}
	
	/**
	* Look for phrases to be auto translated in a string
	*
	* @param	string	$content	string to look for phrases
	* @return	string
	*/
	public function translate(&$content){
		preg_match_all("|\{{([^\}]+)\}}|", $content, $mat,PREG_SET_ORDER);
		foreach($mat as $mat)
			$content = str_replace($mat[0], self::e($mat[1]), $content);
		return $content;
	}
}
