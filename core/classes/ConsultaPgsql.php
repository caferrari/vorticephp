<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Make queryes on Postgres Database
 *
 * @version	1
 * @package	Banco
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class ConsultaPgsql extends Consulta {

	/**
	* Constructor, execute the query on the database
	*
	* @param	string	$sql		SQL query
	* @param	string	$conexao	Connection resource
	* @return	void
	*/
	public function ConsultaPgsql($sql, $conexao){
		if (!$tmpRecordSet = pg_query($conexao->cid, $sql)) throw(new QueryErrorException($sql));
		$this->numReg = pg_num_rows($tmpRecordSet);
		$x=0;
		while ($l = pg_fetch_assoc($tmpRecordSet))
			$this->recordSet[++$x] = $l;
	}
}
