<?php

  namespace Vendor\Database;

	/**
   * @class
	 */	
	class Db_select_order{

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
		public function order($query = false)
    { 
      // spojovnik
      $join = ", ";
      //
      $order = " ORDER BY ";
      //
      // overi, ci parameter splna podmienky
      if (!is_array($query) && 
          $query !== false) 
      {
        // zaznam chyby
        throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Parameter ma byt neprazdne pole!');
      } elseif (is_array($query)) {
        // prechod cez prvky pola
        foreach ($query as $key => $value) {
          // overi, ci je scalar
          if (is_array($value)) {
              // zapis chyby
              throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Hodnota nesmie byt polom!');
          } elseif (is_scalar($value)) {
            // zapis hodnoty
            $order .= $value.$join;
          // ak prva hodnota nie je polom ani skalarom
          } else {
            // zapis chyby
            throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Hodnota musi byt skalar!');
          }
        }
        // orezanie poslednych hodnot
        $this->select_query .= substr($order, 0, strlen($order) - strlen($join));
      }
      // definovanie limity
      return new \Vendor\Database\Db_select_limit($this->mysql, $this->select_query);
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

