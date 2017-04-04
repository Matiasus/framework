<?php
	
namespace Vendor\Config;

class File {

  /** @var Object \Vendor\Config\Parser */
  private $parser = null;

  /** @var Array temporary config values */
  private static $temp = null;

  /** @var Array config values */
  private static $config = array();
  
  /***
  * Constructor
  *
  * @param String - path to INI file
  * @return Void
  */
  public function __construct($path)
  {
    // check if is non empty String
    if (!file_exists($path) ||
        !is_readable($path))
    {
      // throw to exception
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: File <b>'.$path.'</b> is not exists or is not readable!');
    }
    // check if is non empty String
    if (!is_string($path) ||
        !(strlen($path) > 0))
    {
      // throw to exception
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Path must be string with <strong>string length ></strong> 0!');
    }
    // $parameter is not a string || strlen  = 0
    // @param String path to file
    // @return Object \Vendor\Config\Parser
    $this->parser = new \Vendor\Config\Parser($path);
  }
  
  /***
  * Parse ini file
  *
  * @param Void
  * @return Void
  */
  public function parse()
  {
    // check if parser is loaded
    if ($this->parser === null) {
      // parser not loaded - throw to exception
      throw new \Exception("Method <strong>'".__METHOD__."()'</strong> in class <strong>'".__CLASS__."'</strong> must be not NULL!");
    }
    // return @var Object \Vendor\Config\Parser
    self::$config = $this->parser->load();
  }

  /***
  * Getter of array
  *
  * @param key
  * @return Void
  */
  public static function getArray($key)
  {
    // check if 1 argument come
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Just <b>ONE</b> argument accepted!"); 
    }
    // check if non empty value
    if (empty($key)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be <b>NON</b> empty value!"); 
    }
    // check if string
    if (!is_string($key)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be a <b>string</b>!"); 
    }
    // check if exists in array   
    if (!array_key_exists($key, self::$config)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: <b>".$key."</b> does not exist!");
    }
    // return requested value from config array
    return self::$config[$key];
  }
  
  /***
  * Getter
  *
  * @param Void
  * @return Void
  */
  public static function get()
  {
    // copy config array
    self::$temp = self::$config;
    // get requested value from config array
    $value = call_user_func_array(array('self', 'recursion'), func_get_args());
    // null temporary variable
    self::$temp = null;
    // return requested value from config array
    return $value;
  }

  /***
  * Getter
  *
  * @param Void
  * @return Void
  */
  private static function recursion()
  {
    // get function arguments
    $args = func_get_args();
    // check if no arguments
    if (empty($args)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be non empty value!");
    }
    // check if exists key in config file
    if (!array_key_exists(func_get_arg(0), self::$temp)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: <b>".func_get_arg(0)."</b> can't exists in config file!"); 
    }
    // if function arguments are 1 item
    if (func_num_args() == 1) {
      // check if array
      if (is_array(self::$temp[func_get_arg(0)])) {
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: <b>".func_get_arg(0)."</b> can't be array!");    
      }
      // return requested config value
      return self::$temp[func_get_arg(0)];
    }
    // check if array
    if (!is_array(self::$temp[func_get_arg(0)])) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: <b>".func_get_arg(0)."</b> must be array in config file!");
    }
    // temporary stored
    self::$temp = self::$temp[func_get_arg(0)];
    // remove first item of array
    array_shift($args);
    // recursion called by callback
    return call_user_func_array(array('self', 'recursion'), $args);
  }
}
