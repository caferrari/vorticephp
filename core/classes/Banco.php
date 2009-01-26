<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */


/**
 * Class to connect to some databases
 *
 * @version	1
 * @package	Banco
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Banco {
	/**
	* The Connection ID
	*
	* @var		string
	* @static
	*/
	public $cid;
	
	/**
	* The Database name
	*
	* @var		string
	* @access	private
	*/
	private $banco;
	
	/**
	* The Database Host
	*
	* @var		string
	* @access	private
	*/
	private $bHost;
	
	/**
	* The Database User
	*
	* @var		string	
	* @access	private
	*/
	private $bUser;
	
	/**
	* The Database Password
	*
	* @var		string
	* @access	private
	*/
	private $bPass;
	
	/**
	* The Database Name
	*
	* @var		string
	* @access	private
	*/
	private $bDatabase;
	
	/**
	* Are the database connections parms initialized?
	*
	* @var		bool
	* @access	private
	*/
	private $inicializado;
	
	/**
	* Is the connection established?
	*
	* @var		bool
	* @access	private
	*/
	private $conectado;
	
	/**
	* Singleton instances for N connections
	*
	* @staticvar	array
	* @access		private
	* @static
	*/
	private static $instance = array();
	
	/**
	* Constructor.
	*
	* @return	void
	* @access	private
	*/
	private function __construct (){
		define("BD_MYSQL",  1);
		define("BD_PGSQL",  2);
	}
	
	/**
	* Load a Banco object instance
	*
	* @param	string	$name	string instance nickname
	* @return	Banco			if nickname dont exists, a new instance is made and returned
	*/
	public static function getInstance($name = 'default'){
		if (!isset(self::$instance[$name])) self::$instance[$name] = new self();
		return self::$instance[$name];
	}
  
	/**
	* Initialize a database
	*
	* @param	string	$host		Databse hostname
	* @param	string	$user		Databse user
	* @param	string	$pass		Databse password
	* @param	string	$database	Databse name
	* @param	string	$banco		Databse type
	* @param	string	$banco		Databse type
	* @return	void
	*/
	public function inicializa ($host, $user, $pass, $database, $banco=BD_MYSQL) {
		$this->inicializado = false;
		$this->conectado	= false;
		if ($host!=''){
			$this->bHost = $host;
			$this->bUser = $user;
			$this->bPass = $pass;
			$this->bDatabase = $database;
			$this->banco = $banco;
			$this->inicializado = true;
		}		
	}
	
	/**
	* Make the datbase connection on the fist query and just if this are initialized.
	*
	* @return	void
	*/
	public function conecta(){
		if (!$this->inicializado)	die ("Banco nao inicializado!");
		switch ($this->banco){
			case BD_MYSQL:
				if (!$this->cid = mysql_connect($this->bHost, $this->bUser, $this->bPass, true)) throw(new Exception('Error trying to connect to database<br />' . mysql_error()));
				if (!mysql_select_db($this->bDatabase, $this->cid)) throw(new DatabaseNotFoundException(mysql_error()));	
				break;
			case BD_PGSQL:
				$con_string = "host={$this->bHost} port=5432 dbname={$this->bDatabase} user={$this->bUser} password={$this->bPass}";
				if (!$this->cid = pg_connect($con_string)) throw(new DatabaseNotFoundException(pg_last_error ($this->cid)));
				break;
		}
		$this->conectado = true;
	}

	/**
	* Execute a query on database
	*
	* @param	string	$sql		string	query
	* @return	if "insert into" returns the new inserted id else, the number of afected rows
	*/
	public function executar($sql, $noid=false) {
		if (!$this->conectado) $this->conecta();
		$this->contQuery++;
		$rs = '';
		switch ($this->banco){
			case BD_MYSQL:
				if (!$rs = mysql_query($sql, $this->cid)) throw(new QueryErrorException($sql));
				$rs = mysql_affected_rows($this->cid);
				break;
			case BD_PGSQL:
				if (!$rs = pg_query($this->cid, $sql)) throw(new QueryErrorException($sql));
				$rs = pg_affected_rows($rs);
				break;
		}
		if ($noid) return $rs;
		$sql = strtolower($sql);
		if (ereg("^insert into ([a-zA-Z0-9_]+)", $sql, $mat)){
			$tabela = $mat[1];
			$sql = "SELECT max(id) as nid FROM $tabela";
			$tmp = self::consultar($sql);
			return $tmp->valor("nid");						
		}
		return $rs;
	}
	
	/**
	* Make a database query
	*
	* @param	string	$sql	string query
	* @return	Recordset
	*/
	public function consultar($sql){
		if (!$this->conectado) $this->conecta();
		$this->contQuery++;
		switch ($this->banco){
			case BD_MYSQL:
				return new ConsultaMysql($sql, $this);
			case BD_PGSQL:
				return new ConsultaPgsql($sql, $this);
		}
	}
  
	/**
	* Close database connection
	*
	* @return	void
	*/
	public function desconecta() {
		switch ($this->banco){
			case BD_MYSQL:
				mysql_close($this->cid);
				break;
			case BD_PGSQL:
				pg_close($this->cid);
				break;
		}
	}
	
	/**
	* Load a value from any table
	*
	* @param	string	$tabela	database table
	* @param	string	$campo	table field
	* @param	int		$id record id
	* @return	string
	*/
	public function pegaValor($tabela, $campo, $id){
		$sql = "SELECT $campo FROM $tabela WHERE id=$id";
		$rsTmp = $this->consultar($sql);
		return $rsTmp->valor($campo);
	}
	
	/**
	* Begin a transition
	*
	* @return	void
	*/
	public function begin(){
		$this->executar("BEGIN WORK");
	}
	
	/**
	* Commit a transition
	*
	* @return	void
	*/
	public function commit(){
		$this->executar("COMMIT");
	}
	
	/**
	* Load the bigger id from a table
	*
	* @param	string	$tabela		database table
	* @return	int
	*/
	public function ultimoId($tabela){
		$sql = "SELECT max(id) as n FROM $tabela";
		$rsTemp = $this->consultar($sql);
		return $rsTemp->valor('n');
	}
}
