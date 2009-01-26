<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Class to make database queryes
 *
 * @version	1
 * @package	Banco
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Consulta {
	/**
	* Query returned data
	*
	* @var	array
	*/
	public $recordSet = array();
	
	/**
	* Recordset pointer
	*
	* @var		int
	* @access	private
	*/
	private $ponteiro = 0;
	
	/**
	* Recordset records
	*
	* @var	int
	*/
	public $numReg = 0;
	
	/**
	* Move to the next record
	*
	* @return	array	 record or false if EOF
	*/
	public function proximo(){
		if ($this->ponteiro < $this->numReg) $this->ponteiro++;
		else return false;
		return $this->recordSet[$this->ponteiro];
	}
	
	/**
	* Move to the first record
	*
	* @return	void
	*/
	public function primeiro(){
		$this->ponteiro = 0;
	}
	
	/**
	* A value of atual record
	*
	* @param	string	$campo	string 	field name
	* @return	string			field value or "" if field dont exists
	*/
	public function valor($campo){
		if ($this->ponteiro==0) $this->proximo();
		if (!isset($this->recordSet[$this->ponteiro][$campo])) return "";
		else return stripslashes($this->recordSet[$this->ponteiro][$campo]);
	}
	
	/**
	* Dump the recordset data
	*
	* @return	void
	*/
	public function dump(){
		echo "<pre>";
		echo var_dump($this->recordSet);
		echo "</pre>";
	}
}
