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

  /** @var */
  private $_interface = null;
 
  /** @var */
  private $_instances = array();

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
   * Create and store service
   *
   * @param  
   * @return 
   */
  public function service ($class = false)
  {
    // check if non empty value
    if (func_num_args() > 1) {
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
    // return reuired instance of class
    return $this->_instances[$class] = $this->resolve($class);
  }

  /***
   * Getter of stored service
   *
   * @param  String
   * @param  Bool - Throw to exception
   * @return 
   */
  public function get ($name = false, $error = false)
  {
    // check if non empty value
    if (func_num_args() > 2) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>2</b> arguments allowed!"); 
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
    if (!array_key_exists($name, $this->_instances) && 
        $error = true) 
    {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class <b>'$name'</b> doesn't exists!");
    // service no exists
    } else if ($error = false) {
      // no exists
      return null;
    }
    // return service
    return $this->_instances[$name];
  }

  /***
   * Set arguments
   *
   * @param  String  
   * @return Void
   */
  public function bind ($name, $arguments)
  {
    // store arguments
    $this->arguments[$name] = $arguments;
  }

  /***
   * Resolve instnce of required instance of class
   *
   * @param  String  
   * @return Void
   */
  public function resolve ($class)
  {
    // if already instantiated
    if (array_key_exists($class, $this->_instances)) {
      // return stored instance
      return $this->_instances[$class];
    }
    // dependencies
    $dependencies = array();
    // create instance of class
    $reflection = new \ReflectionClass($class);
    // check if can be created
    if (!$reflection->isInstantiable()) {
      // if is not interface
      if (!$reflection->isInterface()) {
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Class <b>'".$class."'</b> is not instantiable!");
      }
      // check if binded param with class interface
      if (is_null($instance = $this->Iexecute($class))) {
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Instance <b>'".$class."'</b> can't be create! Bind param not defined");
      }
      // Interface execute
      return $this->_instances[$class] = $this->Iexecute($class);
    }
    // get created class constructor
    $constructor = $reflection->getConstructor();
    // check if constructor present
    // or has no parameters
    if (is_null($constructor) ||
       empty($constructor->getParameters())) 
    {
      // check if backslash present
      if (strcmp($class[0], '\\') !== 0) {
        // append backslash
        $class = '\\'.$class;
      }
      // return new instance
      return $this->_instances[$class] = new $class;
    }
    // constructor parameters
    $parameters = $constructor->getParameters();
    // if there is dependencies - loop through dependencies
    foreach ($parameters as $parameter) {
      // check if parameter is class
      if (!is_null($parameter->getClass())) {
        // constructor dependencies with recursion
        $dependencies[] = $this->resolve($parameter->getClass()->getName());
      } else {
        // if optional value, try to get default value
        if ($parameter->isDefaultValueAvailable()) {
          // get default value
          $dependencies[] = $parameter->getDefaultValue();
        } else {
          // get default value
          $dependencies[] = $this->append($class);
        }
      }
    }
    // return instance with parameters
    return $this->_instances[$class] = $reflection->newInstanceArgs($dependencies);

  }

  /***
   * Execute interface
   *
   * @param  String  
   * @return NULL | instance
   */
  private function Iexecute ($interface) 
  {
    // store interface
    $this->_interface = $interface;
    // check if backslash present
    if (strcmp($this->_interface[0], '\\') !== 0) {
      // append backslash
      $this->_interface = '\\'.$interface;
    }
    // check if array non empty
    if (empty($this->arguments)) {
      // unsuccess
      return null;
    }
    // check if in array name of interface exists
    if (!array_key_exists($this->_interface, $this->arguments)) {
      // unsuccess
      return null;
    }
    // return defined instance
    // --------------------------------------------------
    // !!! Closure object must be called with brackets ()
    // --------------------------------------------------
    return $this->arguments[$this->_interface]();
  }

  /***
   * Append parameters
   *
   * @param  String  
   * @return NULL | instance
   */
  private function append ($instance) 
  {
    // check if backslash present
    if (strcmp($instance[0], '\\') !== 0) {
      // append backslash
      $instance = '\\'.$instance;
    }
    // check if array non empty
    if (empty($this->arguments)) {
      // unsuccess
      return null;
    }
    // check if in array name of interface exists
    if (!array_key_exists($instance, $this->arguments)) {
      // unsuccess
      return null;
    }
    // return defined instance
    return $this->arguments[$instance];
  }
}

