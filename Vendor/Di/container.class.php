<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:        Mato Hrinko
* Datum:        20.03.2017
* Adresa:       http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Vendor\Di;

class Container {

  /** @var */
  private $reflection;

  /***
   * Constructor
   *
   * @param  \Vendor\Reflection\Reflection
   * @return Void
   */
  public function __construct (\Vendor\Reflection\Reflection $reflection)
  {
    // @var \Vendor\Reflection\Reflection
    $this->reflection = $reflection;
  }

  /***
   * Store service 
   *
   * @param  String
   * @param  Array
   * @return Void
   */
  public function store ($class = false, $arguments = false)
  {
    return $this->reflection->createInstance($class, $arguments);
  }

  /***
   * Get service 
   *
   * @param  String
   * @param  Bool
   * @return stdClass
   */
  public function service ($class = false, $error = true)
  {
    return $this->reflection->getInstance($class, $error);
  }
}

