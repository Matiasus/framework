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

/**
 * Interface Connection
 *
 * @param  Void
 * @return Void
 */
interface Iconnection
{
  /**
   * Connect to database 
   *
   * @param  Array
   * @return \PDO
  */	
	public function __construct($parameters = array());

  /**
   * Deactive all connections
   *
   * @param  Void
   * @return Void
   */
  public function __deconstruct();
}
