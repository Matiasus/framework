<?php

  namespace Vendor\Database;

	/**
   * @class
	 */	
	class Db_select_limit{

		/** @var Objekt \Vendor\Mysql\Mysql */
		private $mysql;

    /** @var String */
    private $select_query;

    /***
     * Konstruktor
     *
     * @param \Errors
     * @param String
     * @return Void
     */
		public function __construct(\Vendor\Mysql\Mysql $mysql, $select_query) 
    {
      $this->mysql = $mysql;
      $this->select_query = $select_query;
		}

		/***
		 * 
		 *
		 * @param String
		 * @return Bool
		 */
		public function limit($query = false)
    { 
      //
      $order = " LIMIT ";
      // overi, ci parameter splna podmienky
      if (!is_int($query) && 
          $query !== false) 
      {
        // zaznam chyby
        throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Parameter ma byt cislo!');
      } else {
        // orezanie poslednych hodnot
        $this->select_query .= " LIMIT ".$query;
      }
      // vykonanie
      $this->mysql->executeQuery($this->select_query);
      // hodnoty
      return $this->mysql->getRows();
		}

		/***
		 * 
		 *
		 * @param String
		 * @return Bool
		 */
		public function query()
    {
      // overi ci je vyplnena poziadavka
      if ($this->select_query == '') {
        throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Poziadavka musi byt zadana!');
      }
      // vykonanie
      $this->mysql->executeQuery($this->select_query);
      // hodnoty
      return $this->mysql->getRows(); 
    }
	}

