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

  /** @var */
  private $arguments = null;

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
   * Set arguments
   *
   * @param  String  
   * @return Void
   */
  public function setArguments($arguments)
  {
    // store arguments
    $this->arguments = $arguments;
  }

  /***
   * Resolve instnce of required instance of class
   *
   * @param  String  
   * @return Void
   */
  public function resolve($class)
  {
    // dependencies
    $dependencies = array();
    // create instance of class
    $reflection = new \ReflectionClass($class);
    // get created class constructor
    $constructor = $reflection->getConstructor();

    // if there is no dependencies
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
    // if there is dependencies - loop through dependencies
    foreach ($constructor->getParameters() as $parameter) {
      // check if parameter is class
      if (!is_null($parameter->getClass())) {
        // constructor dependencies with recursion
        $dependencies[] = $this->resolve($parameter->getClass()->getName());
      // is non class
      } else {
        // if optional value, try to get default value
        if (!$parameter->isOptional()) {
          // if not null
          if (!is_null($this->arguments)) {
            // append dependency
            $dependencies[] = $this->arguments;
            // null
            $this->arguments = null;
          }
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

