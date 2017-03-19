<?php

  namespace Vendor\Database;

	/**
   * @class
	 */	
	class Db_select_from{

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
		public function from($query)
    { 
      $on = 'ON';
      // spojovnik
      $join = " ";
      //
      $from = " FROM ";
      //
      $inner_join = 'INNER JOIN ';
      // overi, ci parameter splna podmienky
      if (empty($query) ||
          !is_array($query))
      {
        // zaznam chyby
        throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Parameter ma byt neprazdne pole!');
      } else {
        // prechod cez prvky pola
        foreach ($query as $key => $value) {
          // overi, ci je scalar
          if (is_array($value)) {
              // prvky pola napr. INNER JOIN
              foreach ($value as $condition => $table_or_condition) {
                // overi, ci je polom
                if (is_array($table_or_condition)) {
                  // zapis chyby
                  throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Hodnota nesmie byt polom!');
                // numericka hodnota? => INNER | OUTER (LEFT RIGHT FULL) JOIN Tabulka
                } elseif (is_numeric($condition)) {
                  // zapis hodnoty
                  $from .= $inner_join.$table_or_condition.$join;
                } else {
                  // zapis hodnoty
                  $from .= $on.$join.$condition." = ".$table_or_condition.$join;
                }
              }
          } elseif (is_scalar($value)) {
            // zapis hodnoty
            $from .= $value.$join;
          // ak prva hodnota nie je polom ani skalarom
          } else {
            // zapis chyby
            throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Hodnota musi byt skalar!');
          }
        }
        // orezanie poslednych hodnot
        $this->select_query .= substr($from, 0, strlen($from) - strlen($join));
        // 
        return new \Vendor\Database\Db_select_where($this->mysql, $this->select_query);
      }
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

