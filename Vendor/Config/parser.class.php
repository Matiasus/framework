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
    // check if $path is non empty string
    if (strlen($path) > 0) {
      // set path to INI file
      $this->path = $path;
    }
  }

  /***
  * Load INI file
  *
  * @param Void
  * @return Void
  */
  public function load()
  {
    // return parsed INI file
    return parse_ini_file($this->path, true);
  }
}
