<?php
	
namespace Vendor\Config;

class Parser {

  /** @var String - path to INI file */
  private $path = '';

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
    // set path to INI file
    $this->path = $path;
  }

  /***
  * Load and parse .ini file
  *
  * @param  Void
  * @return Array
  */
  public function load()
  {
    // return parsed INI file
    return parse_ini_file($this->path, true);
  }
}
