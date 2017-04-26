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
class Database{

  /** @const */
  const NOTEQUAL  = 0;

  /** @const */
  const WITHEQUAL = 1;

  /** @const */
  const MYSQL_NOW = "NOW()";

  /** @var Objekt Mysql - spojenie s databazou registry\mysql */
  private $connection;

  /** @var String */
  private $select_query = "SELECT ";

  /***
   * Constructor
   *
   * @param Object \Vendor\Connection\Mysql
   * @return Void
   */
  public function __construct(\Vendor\Connection\Mysql $connection) 
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
    $qrespond = $this->connection
                     ->executeQuery($query);
    // get content
    $content = $this->connection
                    ->getRows();
    // return content
    return $content;
  }

  /***
   * Insert data into database
   *
   * @param  Array  
   * @param  String  
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
        $binds .= $value.", ";     
      }
    }
    // names
    $names = substr($names, 0, strlen($names) - 2);
    // binds
    $binds = substr($binds, 0, strlen($binds) - 2);
    // query string
    $query = "INSERT INTO $table ($names) VALUES ($binds);";
    // execute query
    $this->connection->executeQuery($query, $data);
	}

  /***
   * Selektovanie udajov z tabulky podla hodnot a podmienky
   *
   * @param String
   * @return Bool
   */
  public function select($query = false)
  {
    // spojovnik
    $join = ", ";
    // vychodzi string
    $select = $this->select_query;

    if (empty($query) ||
        !is_array($query))
    {
      // zaznam chyby
      throw new \Exception(get_class($this).'-'.__FUNCTION__.'-'.__LINE__, 'Parameter ma byt neprazdne pole!');
    } else {
      // prechod cez prvky pola
      foreach ($query as $key => $value) {
        // overi, ci je scalar
        if (!is_scalar($value)) {
          // zaznam chyby
          throw new \Exception(get_class($this).'-'.__FUNCTION__.'-'.__LINE__, 'Hodnota musi byt skalar!');
        } else {
          // zapis hodnoty
          $select .= $value.$join;
        }
      }
      // orezanie poslednych hodnot
      $select = substr($select, 0, strlen($select) - strlen($join));
    }
    // uspesny navrat
    return new \Vendor\Database\Db_select_from($this->connection, $select);
	}

  /**
   * Update udajov z tabulky podla hodnot a podmienky
   *
   * @param Array - hodnoty
   * @param Array - podmienka
   * @param String - tabulka
   * @return Bool
   */
  public function update($values = array(), $conditions = array(), $table)
  {
    $value = $this->process($values, self::WITHEQUAL);
    $condition = $this->process($conditions, self::WITHEQUAL, " AND ");

    // Sql prikaz na update udajov do databazy
    $sqlquery = "UPDATE {$table} SET $value WHERE $condition;";

    $this->connection->executeQuery($sqlquery);

    return TRUE;
  }

	/**
	 * Vymazavanie udajov z databazy
	 *
	 * @param Array - hodnoty
	 * @param Array - podmienka
	 * @param String - tabulka
	 * @return Bool
	 */
	public function delete($conditions = array(), $table)
	{
		// spracovanie podmienky vymazania zaznamu
		$condition = $this->process($conditions, self::WITHEQUAL, " AND ");
		// Sql prikaz na vymazanie udajov z databazy 
		$sqlquery = "DELETE FROM {$table} WHERE ".$condition.";";
		// vykonanie dotazu
		$this->connection->executeQuery($sqlquery);

		return TRUE;
	}

	/***
	 * Uprava retazca vhodneho do url adresy
	 *
	 * @param String - retazec, ktory ma byt konvertovany
	 * @return String - konvertovany / upraveny retazec
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

    $clean_url = $replaced;

    // Replaced multiple '-' characters
    if ($delimeter !== '') {
      $clean_url = preg_replace($pattern = "/[".$delimeter."]+/", $replacement = $delimeter, $replaced);
    }

    return $clean_url;
  }

	/***
	 * Odstranenie html tagov
	 *
	 * @param String - retazec, ktory ma byt konvertovany
	 * @return String - konvertovany / upraveny retazec
	 */
  public function stripHtmlTags($string)
  {
    $strip_ent = preg_replace('#&.{1,20};#i', '', $string);
    $strip_tag = preg_replace('#<[^>]+>#i', '', $strip_ent);

    return $strip_tag;
  }

	/***
	 * Spracovanie pola do retazca
	 *
	 * @param Array - spracovavane pole
	 * @return String - spracovane pole do ratazca
	 */
	private function process($data = array(), $by, $join_delimiter = False)
	{
		/**
		** Inicializacia navratovej hodnoty
		*/			
		$hodnota = "";
		($join_delimiter === False) ? $junction = ", " : $junction = $join_delimiter;

		switch ($by)
		{	
			case self::NOTEQUAL:

				if (!empty($data)) {
					foreach ($data as $key => $value)	{
						$hodnota .= $value.$junction;
					}
				}

				/**
				 * Orezanie poslednych dvoch znakov - ciakry a prazdnu medzeru 
				 * retazca do MySql syntaxu
				 */
				$hodnota = substr($hodnota, 0, strlen( $hodnota) - strlen($junction));

				return $hodnota;

			case self::WITHEQUAL:

				if (!empty($data)) {
          // prechod cez jednotlive prvky
					foreach ($data as $key => $value) {
						// Osetrenie pripadu ak je ukladany terajsi datum a cas funkciou NOW()
						if (strrpos($value, self::MYSQL_NOW) === FALSE)	{
							// Ulozenie hodnot s uvodzovkami
							$hodnota .= $key."='".addslashes($value)."'".$junction;
						}	else {
							// Ulozenie caz funkciu sql bez uvodzoviek
							$hodnota .= $key."=".$value.$junction;
						}
					}
				}	else {
					return False;
				}
				/**
				 * Orezanie poslednych dvoch znakov - ciakry a prazdnu medzeru 
				 * retazca do MySql syntaxu
				 */
				$hodnota = substr($hodnota, 0, strlen($hodnota) - strlen($junction));

				return $hodnota;

		}		
	}

	/***
	 * Spracovanie url adresy do retazca
	 *
	 * @param Array - spracovavane pole
	 * @return String - spracovane pole do ratazca
	 */
  public function dateToDatabase($url)
  {
    $length = \Application\Config\Settings::$Detail->Framework->Length_date;
    return substr($url, 0, $length).
									" ".
									strtr(substr($url, $length + 1), $to = "-", $from = ":");
  }
}

