<?php

/**
 * POZNAMKOVYBLOG Copyright (c) 2015 
 * 
 * Autor:        Mato Hrinko
 * Datum:        08.08.2017
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
   * Constructor
   *
   * @param  Array
   * @return Void
   */	
  public function __construct($parameters = array());
  
  /**
   * Connect to database 
   *
   * @param  Void
   * @return Void
   */	
  public function connect();

  /**
   * Deactive all connections
   *
   * @param  Void
   * @return Void
   */
  public function __deconstruct();
}
