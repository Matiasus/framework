<?php

/**
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:        Mato Hrinko
*	Datum:        07.12.2016 / update
*	Address:      http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiration:  
*
***/
namespace Vendor\Connection;

class Mysql {

  /** @const HOST*/
  const HOST = ":host=";

  /** @const DBNAME*/
  const DBNAME = ";dbname=";

  /** @var Array - Docasne ulozenie dat vykonanych z poziadaviek */
  private $data = array();

  /** @var \PDO->prepare - Posledna ziadost	*/
  private $last;

  /** @var Array - Pripojenia k databazam */
  private $connections = array();

  /** @var String Zvolenie aktivnej tabulky	*/
  private $table;

  /** @var String - nazov pripojenia */
  private $name;

  /**
  * Constructor
  *
  * @param  Void
  * @return Void
  */
  public function __construct() 
  {
  }

  /**
  * Connect through PDO
  *
  * @param String - connection name
  * @param String - host - localhost
  * @param String - db name
  * @param String - user
  * @param String - password
  * @param Array  - options
  * @return \PDO
  */		
  public function connect($name, $host, $dbname, $user, $password, $options = array())
  {
    // connection name
    $this->name = $name;
    // if exists connection
    if ($this->existsName() === true) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Connection <b>".$this->name."</b> exists! Please, choose other connection name!!"); 
    }
    // connect through PDO
    try {
      // dsn
      $dsn = 'mysql'.self::HOST.$host.self::DBNAME.$dbname.';';
      // connect to db
      $this->connections[$this->name] = new \PDO($dsn  
                        ,$user
                        ,$password
                        ,$options
                        );
      // set coding to utf8
      $this->connections[$this->name]->exec("set names utf8");
      // active connection
      $this->active = $this->name;
    }
    // Exception occured
    catch(\PDOException $exception) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Error in SQL syntax or query! <b>Error message: </b>".$exception->getMessage());     
    }
    // return \PDO Object
    return $this->connections[$this->name];
  }

  /**
  * Process request
  *
  * @param String 
  * @param Array 
  * @param Bool   - bind param
  * @return Bool
  */
  public function executeQuery($statement, $values = array())
  {
    try {
      // prepare parameters
      $this->last = $this->connections[$this->active]
                         ->prepare($statement);
      // bind params
      foreach ($values as $key => $value)	{
        // bind only if array
        if (is_array($value)) {
          // check if non empty values
          if (empty($value[0]) ||
             (empty($value[1]))) {
            // error message
            throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Doesn't exist required elements of bind array (0, 1)!");
          }
          // check if constant exists
          if (defined($value[1]) === false) {
            // error message
            throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Constant <b>'$value[1]'</b> doesn't exists!");
          }
          // join with values
          $this->last->bindParam(":".$key, $value[0], constant($value[1]));
        }
      }
      // execute query
      $this->last->execute();
    }
    catch(\PDOException $exception) {
      // error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: ERROR with executing \'". $statement."' :<br/>". $exception->getMessage());
    }
  }
  
  /**
  * Connection exists
  *
  * @param  Void
  * @return Boolean
  */
  private function existsName()
  {
    // check if exists?
    if (array_key_exists($this->name
                        ,$this->connections)) {
      // exists
      return true;
    }
    // no exists
    return false;
  }

  /**
  * Check if row exists in table
  *
  * @param  String   
  * @return Bool
  */
  public function existenceOfColumn($column)
  {
    // MySQL Syntax
    $query = "SHOW COLUMNS FROM ".$this->table." LIKE '".ucfirst($column)."';";	
    // execute query
    if ($this->executeQuery($query, array()) === true) {
      // check if number of rows > 0 
      if (count($this->getRows()) > 0) {
        // not exists
        return true;
      }
    }
    // exists
    return true;
  }

  /**
  * Get rows
  *
  * @param  Void
  * @return Object
  */
  public function getRows()
  {
    return $this->last->fetchAll(\PDO::FETCH_OBJ);
  }
  
  /**
  * Get active connection
  *
  * @param  Void
  * @return \PDO
  */
  public function getActiveConnection()
  {
    return $this->connections[$this->active];
  }

  /**
  * Get last inserted Id
  *
  * @param  Void
  * @return Object
  */
  public function lastInsertId()
  {
    return $this->connections[$this->active]->lastInsertId();
  }

  /**
  * Set table
  *
  * @param  String
  * @return Void
  */
  public function setTable($table)
  {
    $this->table = $table;
  }

  /**
  * Get table name
  *
  * @param  Void
  * @return String
  */
  public function getTable()
  {
    return $this->table;
  }

  /**
  * Destructor
  *
  * @param  Void
  * @return Void
  */
  public function __deconstruct() 
  {
    // loop through all connection
    foreach($this->connections as $connection) {
      // disconnect all connection
      $connection = null;
    }
  }
}
