<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:		    Mato Hrinko
*	Datum:		    07.12.2016 / update
*	Adresa:		    http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Vendor\Route;

use \Vendor\Config\File as Config;

class Route {

  /** @const */
  const MODEL       = "Model";
  /** @const */
  const MODULE      = "Module";
  /** @const */
  const CONTROLLER  = "Controller";
  /** @const */
  const APPLICATION = "Application";

  /** @var */
  public $view;

  /** @var */
  public $module;
  
  /** @var */
  public $controller;

  /** @var Array - obsah rozlozenie url cesty	*/
  private static $uri = array();

  /** @var */
  private $controller_namespace = false;

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
  * Get url address in form (http://www.chat.com/show/ubuntu)
  *
  * @param Boolean - true = with http:// or false = without
  * @return String - form of url address
  */
  public static function getSerNameUri($http = false)
  {
    // init uri
    $uri = '';
    // http protocol
    $protocol = 'http://';
    // check if HTTPS set
    if (isset($_SERVER['HTTPS'])) {
      // https protocol
      $protocol = 'https://';
    }
    // with http or https
    if ($http !== false) {
      // url with http
      $uri .= $protocol;
    // case without server name
    }
    // with server name
    return $uri.$_SERVER['SERVER_NAME'];
  }

  /***
  * Get url address in form (http://www.chat.com/show/ubuntu/?call=script)
  *
  * @param Boolean - true = with SERVER NAME or false = without
  * @return String - form of url address
  */
  public static function getReqUri()
  {
    // requested url
    return $_SERVER['REQUEST_URI'];
  }

  /***
  * Get url address in form (http://www.chat.com/)
  *
  * @param Boolean - true = with http:// or false = without
  * @return String - form of url address
  */
  public static function getfullUri($http = false)
  {
    // get server name and requested uri
    return self::getSerNameUri($http).self::getReqUri();
  }

  /***
  * Explode url path
  *
  * @param Void
  * @return Void
  */
  public function explodeUrl()
  {
    // url path
    $path = self::getReqUri();
    // extract from url address module, view, controller
    // in according to config file (section ROUTE[PATT])
    // x param in regex is ignore whitespace in regex
    preg_match('~'.Config::get('ROUTE', 'PATTERN').'~ix', $path, $matches);
    // loop the values
    foreach ($matches as $key => $value) {
      // find string in keys
      if (is_string($key)) {
        // check if value is array & check if exists zero item
        if (!is_array($value) &&
            !empty($value)) 
        {
          // save to uri array
          self::$uri[$key] = trim($value);
        }
      }
    }
  }

  /***
  * Create controller namespace
  *
  * @param  Void
  * @return Void
  */
  public function initValueUrl($http = false)
  {
    // init for controller
    if (self::get('controller') === false) {      
      // controller
      self::$uri['controller'] = Config::get('CONTROLLER', 'CONTROLLER');
    }
    // init for module
    if (self::get('module') === false) {
      // module
      self::$uri['module'] = Config::get('CONTROLLER', 'MODULE');
    }
    // init for view
    if (self::get('view') === false) { 
      // view
      self::$uri['view'] = Config::get('CONTROLLER', 'VIEW');      
    }
    // controller name
    self::$uri['controller_name'] = self::get('controller').self::CONTROLLER;
    // controller full name from parsed url address without Module section
    self::$uri['controller_namespace'] = '\\'.self::APPLICATION.
                                         '\\'.self::CONTROLLER.
                                         '\\'.self::$uri['controller_name'];
    // module exists?
    $module_exists = strpos(strtolower(Config::get('ROUTE', 'PATTERN')), strtolower(self::MODULE));
    // controller full name from parsed url address with Module section
    if ($module_exists !== false) {
      //  controller name from parsed url address with Module section
      self::$uri['controller_namespace'] = '\\'.self::APPLICATION.
                                           '\\'.self::MODULE.
                                           '\\'.ucfirst(self::get('module')).
                                           '\\'.self::CONTROLLER.
                                           '\\'.self::$uri['controller_name'];
    }
  }

  /***
  * Getter
  *
  * @param Void
  * @return String
  */
  public static function get($key = false, $exception = false)
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
    if (!array_key_exists($key, self::$uri)) {
      // exception?
      if ($exception !== false) {
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: <b>".$key."</b> does not exist in ROUTE!");
       } else {
        // not exists
        return false;
       }
    }
    // return value
    return self::$uri[$key];
  }

  /***
  * Redirect to uri
  * http://localhost/admin/home/default
  *
  * @params String - address routing
  * @params Bool  true  - absulute address http://localhost/admin/home/default
  *	 						 false  - relative address admin/home/default
  * @return void
  */
  public static function redirect($addr = false)
  {
    // redirect address
    $redirect = '';
    // http protocol
    $protocol = 'http://';
    // check if HTTPS set
    if (isset($_SERVER['HTTPS'])) {
      // https protocol
      $protocol = 'https://';
    }
    // redirect address
    $redirect = $protocol.self::getSerNameUri();
    // check if first slash is present
    if (strcmp('/', $addr[0]) !== 0) {
      // start with '/'
      $redirect .= '/';
    }
    // redirect url address
    $redirect .= $addr;
    // Redirection
    header( "Location: ".$redirect);
    // Important!
    exit(0);
  }
}
