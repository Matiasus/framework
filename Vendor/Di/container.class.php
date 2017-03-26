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
  private $arguments = null;

  /** @var */
  private $services = array();

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
    // check if non empty value
    if (func_num_args() > 2) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>TWO</b> argument possible!"); 
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
    // if class doesn't exist
    if (!class_exists($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class <b>'$class'</b> doesn't exists!"); 
    }
    // if non empty arguments
    if (!empty($arguments)) {
      // throw to exception with error message
      $this->arguments = $arguments;
    }
    // return reuired instance of class
    return $this->services[$class] = $this->resolve($class);
  }

  /***
   * Get service 
   *
   * @param  String
   * @return Void
   */
  public function service ($name = false)
  {
    // check if non empty value
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>ONE</b> argument possible!"); 
    }    
    // check if non empty value
    if (empty($name)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be <b>NON</b> empty value!"); 
    }
    // check if string
    if (!is_string($name)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be a <b>string</b>!"); 
    }
    // if class doesn't exist
    if (!array_key_exists($name, $this->services)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class <b>'$name'</b> doesn't exists!"); 
    }
    // return service
    return $this->services[$name];
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

