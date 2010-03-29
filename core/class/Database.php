<?php
/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>
 * All rights reserved. 
 */
//TODO: Descrições dos metodos e parametros assim como seu tipo de dado
define("BD_MYSQL", 0);
define("BD_PGSQL", 1);
define("BD_MSSQL", 2);
define("BD_SYSBASE", 3);
define("BD_SQLITE", 4);
define("BD_ORACLE", 5);

/**
 * Framework Database class
 * Oracle support by: Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 *
 * @version	1
 * @package	Database
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 */
class Database
{
	/**
	* Databases instances
	* @staticvar	array
	* @access		private
	*/
	private static $instances = array();
	
	/**
	* Database connection instance
	* @var			PDO
	* @access		private
	*/
	public $pdo = null;
	
	/**
	* Database connection parameters
	* @var			array
	* @access		private
	*/
	private $pars = false;
	
	/**
	* Database stablished flag
	* @var			Boolean
	* @access		private
	*/
	private $connected = false;
	
	/**
	* Databases connection strings
	* @var			Array
	* @access		private
	*/
	private $bd_str = array(
		'mysql:dbname=%dbase;host=%host',
		'pgsql:dbname=%dbase;user=%user;password=%pass;host=%host',
		'mssql:host=%host;dbname=%dbase',
		'sybase:host=%host;dbname=%dbase',
		'sqlite:%host',
		'oci:dbname=%dbase;charset=AL32UTF8'
	);

	/**
	* Prepared SQL instructions
	* @var			Array
	* @access		private
	*/
	private $prepared = array();


	/**
	* Constructor
	* @return	void
	*/
	private function __construct(){}

	/**
	* Return a database class instance
	* @param    string   $name  description TODO: add description
	* @param    datetype $env   description TODO: add datetype and description
	* @return	Database TODO: add description
	*/
	public static function getInstance($name='default', $env = environment)
	{
		
		if (!isset(self::$instances[$name . '_' . $env]))
			self::$instances[$name . '_' . $env] = new Database();
		return self::$instances[$name . '_' . $env];
	}
	
	/**
	* Create a new database instance for a specific environment
	* @param    datetype    $env       description TODO: add datetype and description
	* @param    datetype    $instance  description TODO: add datetype and description
	* @return	void
	*/
	public static function load($env, $instance='default')
	{
		return Database::getInstance($instance, $env);
	}
	/**
	 * Initialize a Database instance
	 * 
	 * @param $host 
	 * @param $user
	 * @param $pass
	 * @param $database
	 * @param $type
	 * @return	void
	 */
	public function init($host='', $user=null, $pass=null, $database='', $type=0)
	{
		$this->pars = array(
			'host'  => $host,
			'user'  => $user,
			'pass'  => $pass,
			'dbase' => $database,
			'type'  => $type
		);
	}
	
	/**
	* Connect to a database if initialized
	* @return	void
	*/
	public function connect(){
		if ($this->connected) return;
		if ($this->pars == false) throw new Exception('Database not inicialized!');
		
		$dsn = $this->bd_str[$this->pars['type']];
		foreach ($this->pars as $k => $v)
			$dsn = str_replace("%$k", $v, $dsn);
		
		try {
			$this->pdo = new PDO($dsn, $this->pars['user'], $this->pars['pass']);
		}catch (PDOException $e){
    		exit ('Database connection failed: ' . $e->getMessage());
		}
		
		$this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connected = true;
	}

	/**
	* Return the PDO Object
	* @return	PDO
	*/
	public function &getPDO()
	{
		return $this->pdo;
	}

	/**
	* Prepare a SQL Statement
	* @param    $sql
	* @return	PDOStatement
	*/
	public function &prepare($sql)
	{
		$this->connect();
		$key = md5($sql);
		if (!isset($this->prepared[$key])) $this->prepared[$key] = $this->pdo->prepare($sql);
		return $this->prepared[$key];
	}

	/**
	* Execute a SQL
	* @return	Integer
	*/
	public function exec($sql, $pars=null)
	{
		$this->connect();
		$query = $this->prepare($sql);
		$rows_afected = $query->execute($pars);
		
		if(preg_match('/^insert into ([a-zA-Z0-9\-_]+)/', strtolower($sql), $match)){
			try{
				return $this->pdo->lastInsertId(($this->pars['type'] == BD_PGSQL) ? $sequence = $match[1].'_id_seq' : null);
			}catch (Exception $e){ }
		}
		return $rows_afected;
	}
	/**
	* Execute a SQL query
	* @return	Array
	*/
	public function query($sql, $object='DTO')
	{
		$this->connect();
		$rs = $this->pdo->query($sql);
		$rs->setFetchMode(PDO::FETCH_CLASS, $object);
		return $rs->fetchAll();
	}
	
	/**
	* Prepare a SQL Query with one result
	* @return	Object
	*/
	public function queryOne($sql, $object='DTO')
	{
		$this->connect();
		$rs = $this->pdo->query($sql);
		return $rs->fetchObject($object);
	}
	
	/**
	* Return the max value of a field of a table
	* @return	Integer
	*/
	public function max($table='', $field='id'){
		$sql = 'SELECT max(' . $field . ') as n FROM ' . $table;
		return $this->queryOne($sql)->n;
	}
	
	/**
	* Return the last inserted id of a table
	* @return	Integer
	*/
	public function lastID($table){
		return $this->max($table);
	}
	
	/**
	* Begin a transaction
	* @return	void
	*/
	public function begin(){
		$this->connect();
		$this->pdo->beginTransaction();
	}
	
	/**
	* Commit a transaction
	* @return	void
	*/
	public function commit(){
		$this->connect();
		$this->pdo->commit();
	}
	
	/**
	* Rollback a transaction
	* @return	void
	*/
	public function rollBack(){
		$this->connect();
		$this->pdo->rollBack();
	}
}
