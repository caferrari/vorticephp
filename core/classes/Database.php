<?php
/* 
 * Copyright (c) 2009, Carlos André Ferrari <[carlos@]ferrari.eti.br>
 * All rights reserved. 
 */

define("BD_MYSQL", 0);
define("BD_PGSQL", 1);
define("BD_MSSQL", 2);
define("BD_SYSBASE", 3);

/**
 * Framework Database class
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
	private $pdo = null;
	
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
		'sybase:host=%host;dbname=%dbase'
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
	* @return	Database
	*/
	public static function getInstance($name="default")
	{
		if (!isset(self::$instances[$name]))
			self::$instances[$name] = new DataBase();
		return self::$instances[$name];
	}

	/**
	* Initialize a Database instance
	* @return	void
	*/
	public function init($host='', $user='', $pass='', $database='', $type=0)
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
		if ($this->pars == false) throw new Exception("Database not inicialized!");
		
		$dsn = $this->bd_str[$this->pars['type']];
		foreach ($this->pars as $k => $v)
			$dsn = str_replace("%$k", $v, $dsn);
		
		try {
			switch ($this->pars['type']){
				case BD_PGSQL:
					$this->pdo = new PDO($dsn);
					break;
				default:
					$this->pdo = new PDO($dsn, $this->pars['user'], $this->pars['pass']);
					break;
			}
		}catch (PDOException $e){
    		exit ('Database connection failed: ' . $e->getMessage());
		}
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
	public function exec($sql, $pars=false)
	{
		$this->connect();
		if ($pars){
			$query = $this->prepare($sql);
			return $query->execute($pars);
		}
		return $this->pdo->exec($sql);
	}

	/**
	* Execute a SQL query
	* @return	Array
	*/
	public function query($sql, $o = "DTO")
	{
		$query = $this->prepare($sql);
		if (is_array($o))
			$query->execute($o);
		else
			$query->execute();
		return $query->fetchAll(PDO::FETCH_OBJ);
	}
	
	/**
	* Prepare a SQL Query with one result
	* @return	Object
	*/
	public function queryOne($sql, $object = "DTO")
	{
		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetch(PDO::FETCH_OBJ);
	}
	
	/**
	* Return the max value of a field of a table
	* @return	Integer
	*/
	public function max($table='', $field='id'){
		$sql = "SELECT max($field) as n FROM $table";
		$query = $this->prepare($sql);
		$query->execute();
		$obj = $query->fetch(PDO::FETCH_OBJ);
		return $obj->n;
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
		$this->pdo->exec("BEGIN;");
	}
	
	/**
	* Commit a transaction
	* @return	void
	*/
	public function commit(){
		$this->pdo->exec("COMMIT;");
	}
	
	/**
	* Rollback a transaction
	* @return	void
	*/
	public function rollback(){
		$this->pdo->exec("ROLLBACK;");
	}
}
