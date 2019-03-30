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
     * @param \Vendor\Connection\Iconnection
     * @param String
     * @return Void
     */
		public function __construct(\Vendor\Connection\Iconnection $mysql, $select_query) 
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
      // joiner
      $join = " ";
      // condition
      $where = " WHERE ";
      // check if non empty array
      if (!empty($query)) {
        if (!is_array($query)) {
          // zaznam chyby
          throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Parameter must be not empty array!');
        // non empty array
        } else {
          // loop through items
          foreach ($query as $key => $value) {
            // if array?
            if (is_array($value)) {
              // prechadza podmienky
              foreach ($value as $keyin => $valuein) {
                // if non scalar
                if (!is_scalar($valuein)) {
                  // throw to exception
                  throw new \Exception('[CLASS:] '.get_class($this).' [FUN:] '.__FUNCTION__.' [LINE:] '.__LINE__.' Value must be scalar!');
                // scalar
                } else {
                  // if number
                  if (is_int($keyin)){
                    // form condition text
                    $comp = $join.$valuein.$join;
                  // if non number
                  } else {
                    // if value is int or is function
                    if (is_int($valuein) ||
                        strpos($valuein, "(") > 0){
                      // generated condition
                      $where .= $keyin.$comp.$valuein.$join;
                    } else {
                      // generated condition
                      $where .= $keyin.$comp.'\''.$valuein.'\''.$join;
                    } 
                  }
                }
              }
            } else {
              // join together
              $where .= $value.$join;
            }
          }
          // trim joiner
          $this->select_query .= substr($where, 0, strlen($where) - strlen($join));
        }
      }
      // return value
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
      // execute
      $this->mysql->execute($this->select_query);
      // get records
      return $this->mysql->getRows();
    }
	}

