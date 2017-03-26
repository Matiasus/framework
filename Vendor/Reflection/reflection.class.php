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
namespace Vendor\Reflection;


class Reflection {

  /***
   * Constructor
   *
   * @param  Void
   * @return Void
   */
  public function __construct ()
  {
  }

  /***
   * Create
   *
   * @param  String
   * @return Void
   */
  public function create ($class = false)
  {
    // check if non empty value
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>ONE</b> argument possible!"); 
    }    
    // check if non empty value
    if (empty($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be <b>NON</b> empty value!"); 
    }
    // check if string
    if (!is_string($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be a <b>string</b>!"); 
    }
    //
    if (!class_exists($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class <b>'$class'</b> doesn't exists!"); 
    }
    // return reuired instance of class
    return $this->resolve($class);
  }

  /***
   * Resolve instnce of reuired instance of class
   *
   * @param  String  
   * @return Void
   */
  private function resolve ($class)
  {
    // dependencies
    $dependencies = array();
    // create instance of class
    $reflection = new \ReflectionClass($class);
    // get created class constructor
    $constructor = $reflection->getConstructor();

    // check if constructor don't content any dependencies
    if (null === $constructor) {
      // check if instantiable
      if (!$reflection->isInstantiable()) {
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class is instantiable!"); 
      }
      // if instance already created
      if (array_key_exists($class, $this->instances)) {
        // return existed instance
        return $this->instances[$class];
      }
      // return new instance
      return new $class;
    }
    // loop through parameters
    foreach ($constructor->getParameters() as $parameter) {
      // check if parameter is class
      if (!is_null($parameter->getClass())) {
        // constructor dependencies with recursion
        $dependencies[] = $this->instances[$parameter->getClass()->getName()] = $this->resolve($parameter->getClass()->getName());
      // is non class
      } else {
        // if optional value, try to get default value
        if (!$parameter->isOptional()) {
          // throw to exception with error message
          throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Parameter is not optional!"); 
        } else {
          // check if default value is available
          if (false === $parameter->isDefaultValueAvailable()) {
            // throw to exception with error message
            throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Default value is not available!");
          } else {
            // get default value
            $dependencies[] = $parameter->getDefaultValue();
          }
        }
      }
    }
    // return instance with parameters
    return $reflection->newInstanceArgs($dependencies);
  }
}

