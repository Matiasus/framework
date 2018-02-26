<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:        Mato Hrinko
* Datum:        07.12.2016 / update
* Adresa:       http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiration: 		
*
***/
namespace Vendor\Database;

// use session class
use \Vendor\Session\Session as Session;

/** @class Database */	
class Database {

  /** @const */
  const NOTEQUAL  = 0;

  /** @const */
  const WITHEQUAL = 1;

  /** @const String */
  const SELECT = "SELECT ";
	
  /** @const */
  const MYSQL_NOW = "NOW()";

  /** @var \Vendor\Connection\Iconnection */
  private $connection;

  /***
   * @desc    Constructor
   *
   * @param   \Vendor\Connection\Iconnection
   *
   * @return  Void
   */
  public function __construct(\Vendor\Connection\Iconnection $connection) 
  {
    // @var \Vendor\Connection\Mysql
    $this->connection = $connection;
  }

  /**
   * @desc   Sql query
   *
   * @param  String
   *
   * @return Array Or False	
   */
  public function query($query)
  {
    // check if non empty value
    if (empty($query)) {
      // throw to exception with error message
      Session::set("flash", "[".get_called_class()."]:[".__LINE__."]: Query must be <b>NON</b> empty value!");
      // unsuccess return
      return false;      
    }
    // check if string
    if (!is_string($query)) {
      // throw to exception with error message
      Session::set("flash", "[".get_called_class()."]:[".__LINE__."]: Query must be <b>string</b>!"); 
      // unsuccess return
      return false;
    }
    // execute query
    $this->connection->execute($query);
    // get content
    $content = $this->connection->getRows();
    // return content
    return $content;
  }

  /***
   * @desc   Insert data into database
   *
   * @param  Array  
   * @param  String
   *
   * @return Void
   */
  public function insert($data = array(), $table = false)
  {
    // binds
    $binds = "";
    // names
    $names = "";
    // check if non empty value
    if (empty($data)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Data must be <b>NON</b> empty array!");
    }
    // check if 2 arguments come
    if (func_num_args() < 2) {
      //
      if (empty($table = $this->connection->getTable())) {
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Table not selected!");
      }
      // throw to exception with error message
      Session::set("flash", "[".get_called_class()."]:[".__LINE__."]: Don't select table. So it will be use <b>".$this->connection->getTable()."</b> table!");
    } 
    // looping array
    foreach ($data as $key => $value) {
      // names
      $names .= $key.", ";
      // is array?
      if (is_array($value)) {
        // bind params
        $binds .= ":".$key.", ";
      } else {
        // bind params
        $binds .= "'".$value."', ";     
      }
    }
    // names
    $names = substr($names, 0, strlen($names) - 2);
    // binds
    $binds = substr($binds, 0, strlen($binds) - 2);
    // query string
    $query = "INSERT INTO $table ($names) VALUES ($binds);";
    // execute query
    $this->connection->execute($query, $data);
  }

  /***
   * @desc    Select from databse
   *
   * @param   String
   *
   * @return  Bool
   */
  public function select($query = false)
  {
    // joiner
    $join = ", ";
    // init value
    $select = self::SELECT;

    if (empty($query) ||
        !is_array($query))
    {
      // throw to error
      throw new \Exception(get_class($this).'-'.__FUNCTION__.'-'.__LINE__, 'Query must be non empty string!');
    } else {
      // loop
      foreach ($query as $key => $value) {
        //is scalar
        if (!is_scalar($value)) {
          // throw to error
          throw new \Exception(get_class($this).'-'.__FUNCTION__.'-'.__LINE__, 'Item of query must be scalar!');
        } else {
          // join with value
          $select .= $value.$join;
        }
      }
      // substring delimeter
      $select = substr($select, 0, strlen($select) - strlen($join));
    }
    // success & return \Vendor\Database\Db_select_from
    return new \Vendor\Database\Db_select_from($this->connection, $select);
  }

  /**
   * @desc    Update data
   *
   * @param   Array
   * @param   Array
   * @param   String
   *
   * @return  Bool
   */
  public function update($values = array(), $conditions = array(), $table)
  {
    // prepare value
    $value = $this->process($values, self::WITHEQUAL);
    // prepare condition
    $condition = $this->process($conditions, self::WITHEQUAL, " AND ");
    // query string
    $query = "UPDATE {$table} SET $value WHERE $condition;";
    // execute query
    $this->connection->execute($query);
    // success
    return TRUE;
  }

  /**
  * @desc   Delete itmes
  *
  * @param  Array
  * @param  Array
  * @param  String
  *
  * @return Bool
  */
  public function delete($conditions = array(), $table)
  {
    // condition 
    $condition = $this->process($conditions, self::WITHEQUAL, " AND ");
    // query string
    $query = "DELETE FROM {$table} WHERE ".$condition.";";
    // execute qeury string
    $this->connection->execute($query);
    // success
    return TRUE;
  }
  /**
  * @desc   Check if row exists in table
  *
  * @param  String
  *
  * @return Bool
  */
  public function columnExists($column, $table)
  {
    // MySQL Syntax
    $query = "SHOW COLUMNS FROM ".$table." LIKE '".ucfirst($column)."';";	
    // execute query
    $this->connection->execute($query);
    // check if number of rows > 0 
    if (empty($this->connection->getRows())) {
      // throw to exception with error message
      Session::set("flash", "[".get_called_class()."]:[".__LINE__."]: Column <b>$column</b> in table <b>$table</b> doesn't exists!"); 
      // unsuccess return
      return false;
    }
    // exists
    return true;
  }

/***
 * @desc Edit url address
 *
 * @param   String
 *
 * @return  String
 */
  public function unAccentUrl($string, $delimeter = '-')
  {
    // Trim empty characters
    $string = trim($string);
    /***
     * Á: &Aacute;
     * À: &Agrave;
     * Â: &Acirc;
     * Ã: &Atilde;
     * Ä: &Auml;
     * Å: &Aring;
     * Æ: &AElig;
     * Ç: &Ccedil;
     * Ø: &Oslash;
     * Č: &Ccaron;
     * Ĳ: &IJlig;
     * Ŀ: &Lmidot;
     * Ī: &Imacr;
     */  
    $utf8_name  = "acute|";
    $utf8_name .= "grave|";
    $utf8_name .= "cedil|";
    $utf8_name .= "slash|";
    $utf8_name .= "caron|";
    $utf8_name .= "tilde|";
    $utf8_name .= "midot|";
    $utf8_name .= "circ|";
    $utf8_name .= "macr|";
    $utf8_name .= "ring|";
    $utf8_name .= "uml|";
    $utf8_name .= "lig|";
    $utf8_name .= "orn|";
    $utf8_name .= "th";
    // Convert string to htm entities 
    $string = htmlentities($string, ENT_HTML5 | ENT_QUOTES, 'UTF-8');
    // Find html entites defined by &[a-zA-Z]$utf8_name;
    // example see above $utf8_name
    $pattern = "/&([a-z]{1,2})(?:".$utf8_name.");/i";
    // Replace html entities by given char
    // note: array of replacement chars is placed in first item of found array
    $converted = strtolower(preg_replace($pattern, $replacement = '$1', $string));
    // Replaced the rest untranslited characters
    $replaced = preg_replace($pattern = "/[^a-z0-9]/", $replacement = $delimeter, $converted);
    //
    $clean_url = $replaced;
    // Replaced multiple '-' characters
    if ($delimeter !== '') {
      $clean_url = preg_replace($pattern = "/[".$delimeter."]+/", $replacement = $delimeter, $replaced);
    }
    // return value
    return $clean_url;
  }

/***
 * @desc    Remove html tags
 *
 * @param   String
 *
 * @return  String
 */
  public function stripHtmlTags($string)
  {
    $strip_ent = preg_replace('#&.{1,20};#i', '', $string);
    $strip_tag = preg_replace('#<[^>]+>#i', '', $strip_ent);

    return $strip_tag;
  }
}

