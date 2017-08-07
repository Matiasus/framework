<?php

/**
 * POZNAMKOVYBLOG Copyright (c) 2015 
 * 
 * Autor:        Mato Hrinko
 * Datum:        07.12.2016 / update
 * Address:      http://poznamkovyblog.cekuj.net
 * 
 * ------------------------------------------------------------
 * Inspiration:  
 *
 */
namespace Vendor\Connection;

class Mysql implements \Vendor\Connection\Iconnection {

  /** @var \PDO Object - last connection	*/
  private $last = null;

  /** @var String - user name */
  private $user = null;

  /** @var String - active connection */
  private $active = null;

  /** @var Array - Temporarty data storage from queries */
  private $data = array();

  /** @var Array - PDO parameters */
  private $parameters = array();

  /** @var Array - \PDO connections */
  private $connections = array();

  /**
   * Constructor
   *
   * @param  String - dsn
   * @param  String - user
   * @param  String - password
   * @param  Array  - options
   * @return Void
   */
  public function __construct($parameters = array()) 
  {
    // active dsn connection
    $this->parameters = $parameters;
    // connect to db
    $this->connect();
  }

  /**
   * Connect to database 
   *
   * @param  Void
   * @return \PDO
  */		
  public function connect()
  {
    // check if non empty value
    if (empty($this->parameters[0])) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be <b>NON</b> empty value!"); 
    }    
    // if exists connection
    if (array_key_exists($this->active, $this->connections)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Connection <b>".$this->active."</b> exists! Please, choose other connection name!!"); 
    }
    // connect with PDO
    try {
      // connect to db
      $this->connections[$this->active = $this->parameters[0]] = new \PDO(
         $this->active 
        ,$this->parameters[1]
        ,$this->parameters[2]
        ,$this->parameters[3]
      );
    }
    // Exception occured
    catch(\PDOException $exception) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Error in SQL syntax or query! <b>Error message: </b>".$exception->getMessage());     
    }
    // return \PDO Object
    return $this->connections[$this->active];
  }

  /**
   * Process request
   *
   * @param  String 
   * @param  Array 
   * @return Void
   */
  public function execute($statement, $values = array())
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
   * Get rows
   *
   * @param  Void
   * @return Object
   */
  public function getRows()
  {
    // array of all items or zero array
    return $this->last->fetchAll(\PDO::FETCH_OBJ);
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
