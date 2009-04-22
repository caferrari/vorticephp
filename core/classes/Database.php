<?php

define("BD_MYSQL", 0);
define("BD_PGSQL", 1);
define("BD_MSSQL", 2);
define("BD_SYSBASE", 3);

class Database
{
	private static $instances = array();
	private $pdo = null;
	private $pars = false;
	private $connected = false;
	private $bd_str = array(
		'mysql:dbname=%dbase;host=%host',
		'pgsql:host=%host port=5432 dbname=%dbase user=%user password=%pass',
		'mssql:host=%host;dbname=%dbase',
		'sybase:host=%host;dbname=%dbase'
	);

	private function __construct()
	{

	}

	public static function getInstance($name="default")
	{
		if (!isset(self::$instances[$name]))
			self::$instances[$name] = new DataBase();
		return self::$instances[$name];
	}

	
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
	
	public function connect(){
		if ($this->connected) return;
		if ($this->pars == false) throw new Exception("Database not inicialized!");
		
		$dsn = $this->bd_str[$this->pars['type']];
		foreach ($this->pars as $k => $v)
			$dsn = str_replace("%$k", $v, $dsn);
		
		switch ($this->pars['type']){
			case 1:
				$this->pdo = new PDO($dsn);
				break;
			default:
				$this->pdo = new PDO($dsn, $this->pars['user'], $this->pars['pass']);
				break;
		}
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connected = true;
		return $this;
	}

	function &getPDO()
	{
		return $this->pdo;
	}

	public function prepare($sql)
	{
		$this->connect();
		return $this->pdo->prepare($sql);
	}

	public function exec($sql)
	{
		$this->connect();
		return $this->pdo->exec($sql);
	}

	public function query($sql, $object = "DTO")
	{
		$this->connect();
		return $this->pdo->query($sql, is_object($object) ? PDO::FETCH_INTO : PDO::FETCH_CLASS, $object);
	}
	
	public function queryOne($sql, $object = "DTO")
	{
		$this->connect();
		return $this->query($sql, $object)->fetch();
	}
}

