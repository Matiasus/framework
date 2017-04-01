<?php

  namespace Vendor\Autoloader;

  /***
  * Poznamkovyblog Copyright (c) 2015 
  * 
  * Author:         Mato Hrinko
  * Datum:          12.7.2015
  * Adrress:        http://matiasus.cekuj.net
  * 
  * ------------------------------------------------------------
  * @description: Autoloading tried - vytvorenie objektov (instancii) je vykonavane jednoduchym volanim triedy, 
  *               nie je potrebne definovanie celej cesty umiestnenie triedy napr. $class = new Class(), namiesto
  *               require(ADRESA ULOZENIA TRIEDY); $class = new Class();
  */
class Autoloader{

  /** @const */
  const EXTENSION_PHP = ".php";

  /** @const */
  const EXTENSION_CLASS = ".class";

  /** @var Array - instancie class */
  private $_class = array();

  /***
  * Constructor
  *
  * @param Void
  * @return Void
  */
  public function __construct()
  {
    spl_autoload_register(array($this, 'autoload'));
  }

  /***
  * Autoloader
  *
  * @param String
  * @return Void
  */
  public function autoload($class)
  {
    // explode namespace
    $class = explode('\\', $class);
    // last element lowercase
    $phpfile = lcfirst(end($class));
    // remove last element
    array_pop($class);
    // implode class
    $file = implode("/", $class);
    // append file
    $file = $file."/".$phpfile;

    // check if exists
    if (file_exists($file. 
                    self::EXTENSION_CLASS. 
                    self::EXTENSION_PHP)) 
    {
      // file with .class.php
      require_once($file.self::EXTENSION_CLASS.self::EXTENSION_PHP);
    // check if exists .php 
    } elseif (file_exists($file.self::EXTENSION_PHP)) {
      // file with .php
      require_once($file.self::EXTENSION_PHP);
    } else {
      // error
      throw new \Exception('\\'.get_class($this).' -> '.__FUNCTION__.' ( ) [Line: '.__LINE__.']: Class '.$file.' not exists!!!');
    }
  }
}



	
