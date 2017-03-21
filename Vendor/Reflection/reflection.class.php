<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:      Mato Hrinko
* Datum:      07.12.2016 / update
* Adresa:     http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Vendor\Reflection;

class Reflection {

  /** @var \ReflectionClass */
  private $reflection;

  /***
   * Constructor
   *
   * @param Void
   * @return Void
   */
  public function __construct()
  {
  }

  /***
   * Reflect class
   *
   * @param Void
   * @return Void
   */
  public function reflect($class = false)
  {
    // check if non empty value
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>ONE</b> allowed!"); 
    }    
    // check if non empty value
    if (empty($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class must be <b>NON</b> empty value!"); 
    }
    // check if string
    if (!is_string($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class (namespace) must be <b>string</b>!"); 
    }  
    // 
    if (!class_exists($class)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Class <b>'.$class.'</b> doesn\'t exist!');
    }
    // create new \ReflectionClass 
    $this->reflection = new \ReflectionClass($class);
  }
  
  /***
   * Create instance of given class
   *
   * @param Void
   * @return Void
   */
  public function create()
  {
    // create reflection
    $this->reflect();
    // get constructor
    $constructor = $this->reflection->getConstructor();
  }
}
