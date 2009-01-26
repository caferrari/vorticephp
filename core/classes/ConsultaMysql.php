<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Make queryes on Mysql Database
 *
 * @version	1
 * @package	Banco
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class ConsultaMysql extends Consulta {
	
	/**
	* Constructor, execute the query on the database
	*
	* @param	string	$sql		SQL query
	* @param	string	$conexao	Connection resource
	* @return	void
	*/
	public function ConsultaMysql($sql, $conexao){
		if (!$tmpRecordSet = mysql_query($sql, $conexao->cid)) throw(new QueryErrorException($sql));
		$this->numReg = mysql_num_rows($tmpRecordSet);
		$x=0;
		while ($l = mysql_fetch_assoc($tmpRecordSet))
			$this->recordSet[++$x] = $l;
	}
}
