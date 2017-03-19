<?php

  namespace Vendor\Database;

	/**
   * @class
	 */	
	class Db_select_where{

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
		 * @param Array | False
		 * @return Bool
		 */
		public function where($query = false)
    { 
      $comp = "";
      // spojovnik
      $join = " ";
      //
      $where = " WHERE ";
      // overi, ci parameter splna podmienky
      if (!empty($query))
        if (!is_array($query))
        {
          // zaznam chyby
          throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Parameter ma byt neprazdne pole!');
        } else {
          // prechod cez prvky pola
          foreach ($query as $key => $value) {
            // overi, ci je scalar
            if (is_array($value)) {
              // prechadza podmienky
              foreach ($value as $keyin => $valuein) {
                if (!is_scalar($valuein)) {
                  // zaznam chyby
                  throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Hodnota musi byt skalar!');
                } else {
                  if (is_int($keyin)){
                    // pospajanie
                    $comp = $join.$valuein.$join;
                  } else {
                    if (is_int($valuein) ||
                        strpos($valuein, "(") > 0){
                      // pospajanie
                      $where .= $keyin.$comp.$valuein.$join;
                    } else {
                      // pospajanie
                      $where .= $keyin.$comp.'\''.$valuein.'\''.$join;
                    } 
                  }
                }
              }
            } else {
              // spojovik podmienok
              $where .= $value.$join;
            }
          }
          // orezanie poslednych hodnot
          $this->select_query .= substr($where, 0, strlen($where) - strlen($join));
       }
       // 
       return new \Vendor\Database\Db_select_order($this->mysql, $this->select_query);
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

