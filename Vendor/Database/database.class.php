<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:        Mato Hrinko
* Datum:        08.04.2018 / update
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
  const MYSQL_NOW = "NOW()";

  /** @var Objekt Mysql - spojenie s databazou registry\mysql */
  private $connection;

  /** @var String */
  private $select_query = "SELECT ";

  /***
   * Constructor
   *
   * @param Object \Vendor\Connection\Iconnection
   * @return Void
   */
  public function __construct(\Vendor\Connection\Iconnection $connection) 
  {
    // @var \Vendor\Connection\Mysql
    $this->connection = $connection;
  }

  /**
   * Sql query
   *
   * @param  String
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
    // query request
    $qrespond = $this->connection->execute($query);
    // get content
    $content = $this->connection->getRows();
    // return content
    return $content;
  }

  /***
   * @desc    Insert data into database
   *
   * @param   Array  
   * @param   String
   *
   * @return  Void
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
    $select = $this->select_query;

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
   * @desc    Update value in db
   *
   * @param   Array
   * @param   Array
   * @param   String
   *
   * @return  Void
   */
  public function update($values = array(), $conditions = array(), $table)
  {
    // value
    $value = $this->toString($values, ", ");
    // condition
    $condition = $this->toString($conditions, " AND ");
    // query string
    $query = "UPDATE {$table} SET $value WHERE $condition;";
    // execute query
    $this->connection->execute($query);
  }

  /**
   * @desc    Delete items
   *
   * @param   Array
   * @param   String
   *
   * @return  Void
   */
  public function delete($conditions = array(), $table)
  {
    // processed condition
    $condition = $this->toString($conditions, " AND ");
    // sql query 
    $query = "DELETE FROM {$table} WHERE ".$condition.";";
    // execute query
    $this->connection->execute($query);
  }

  /**
  * @desc    Check if row exists in table
  *
  * @param   String   
  * @return  Bool
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

  /**
   * @desc    Unaccent url
   *
   * @param   String
   *
   * @return  String
   */
  public function unAccentUrl($string, $delimeter = '-')
  {
    // Trim empty characters
    $string = trim($string);
    // special chars
    $search_chars = array(
      '!', '"', '#', '&', '(', ')', '*', '+', ',', '-', '.',
      '/', ':', ';', '<', '=', '>', '~', '|', '}', ',', ' ',
      '?', '@', '[', ']', '^', '_', '`', '{', '$', '%', 
      '\\', '\'',
    );
    // spacial chars replaced
    $string = str_replace($search_chars, $replaced = " ", $string);
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
    // Find html entites defined by &[a-zA-Z]$utf8_name; example see above $utf8_name
    $pattern = "/&([a-z]{1,2})(?:".$utf8_name.");/i";
    // Replace html entities by given char
    // note: array of replacement chars is placed in first item of found array
    $converted = strtolower(preg_replace($pattern, $replacement = '$1', $string));
    // Replaced the rest untranslited characters
    $replaced = preg_replace($pattern = "/[^a-z0-9]/", $replacement = $delimeter, $converted);
    // clean url
    $clean_url = $replaced;
    // Replaced multiple '-' characters
    if ($delimeter !== '') {
      $clean_url = preg_replace($pattern = "/[".$delimeter."]+/", $replacement = $delimeter, $replaced);
    }
    // return clean url address
    return $clean_url;
  }

  /***
   * @desc    Strip tags
   *
   * @param   String
   *
   * @return  String
   */
  public function stripHtmlTags($string)
  {
    // strip entities
    $strip_ent = preg_replace('#&.{1,20};#i', '', $string);
    // strip tags
    $strip_tag = preg_replace('#<[^>]+>#i', '', $strip_ent);
    // return stripped tags
    return $strip_tag;
  }

  /***
   * @desc    Convert array to string with delimeter
   *
   * @param   Array
   *
   * @return  String
   */
  private function toString($data = array(), $junction = false)
  {
    // init value			
    $string = "";
    // loop
    foreach ($data as $key => $value) {
      // if function NOW()
      if (strrpos($value, self::MYSQL_NOW) === false)	{
        // add value to string
        $string .= $key."='".addslashes($value)."'".$junction;
      }	else {
        // add value to string
        $string .= $key."=".$value.$junction;
      }
    }
    // trim characters
    $string = substr($string, 0, strlen($string) - strlen($junction));
    // return value
    return $string;
	}
}

