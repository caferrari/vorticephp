<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
* Sample of a framework plugin to convert <!--plugin:data--> strings to the current date
* @package SampleApp
* @subpackage Plugins
*/
class Includepage extends Template{
	/**
	* Matches count
	* @var		int
	* @access	private
	*/
	private $match;
	
	/**
	* Execute the plugin
	*
	* @return	void
	*/
	public function __construct(){
		preg_match_all("/<!--plugin:inc:(.*):(.*)-->/", Template::$conteudo, $mat);
		$this->match = count($mat[0]);
		for ($x=0; $x<$this->match; $x++){
			$c = $mat[1][$x];
			$v = $mat[2][$x];
			
			$resp = Template::executar($v, $v, $c);
			Template::setVar("plugin:inc:$c:$v", $resp);
		}
		Template::mergeVars();
		
		if ($this->match > 0) Template::runPlugins();
	}
	
	/**
	* Object to string
	*
	* @return	string
	*/
	public function __toString(){ return "{$this->match}"; }
}
?>
