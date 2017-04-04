<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:        Mato Hrinko
*	Datum:        07.12.2016 / update
*	Adresa:       http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiration:  http://www.pehapko.cz/programujeme-v-php/sessions
*
***/
namespace Vendor\Session;

use \Vendor\Date\Date as Date,
    \Vendor\Config\File as Config;

class Session {

  /** @var index */
  private static $index = 0;

  /** @var Launch session	*/
  private static $launched;

  /** @var Zakladne nastavenia	*/
  private static $configuration = array (
    // Minimalna zivotnost session 
    "session.gc_maxlifetime"   => 0,

    // Zabezpeci, aby poziadavka na session bola realizovana prostrednictvom cookie, nie pomocou GET parametra
    "session.use_cookies"      => 1,
    // Zabezpeci, aby poziadavka na session bola realizovana IBA prostrednictvom cookie
    "session.use_only_cookies" => 1,
    // Musi byt nastavene na 1 - nebude citat ani zahrnovat SID do url
    "session.use_trans_sid"    => 0,

    // zivotnost cookie session
    "session.cookie_lifetime"  => 0,
    // Nastavuje cestu, kde sa ma vytvarat cookie - cookie vramci celej domeny
    "session.cookie_path"      => "/",
    // Nastavuje domenu, v ramci ktorej je mozne pouzivat cookie
    //			"session.cookie_domain" 		=> "",
    // Zabezpeci, ze cookie je pristupne iba prostrednictvom http alebo https
    "session.cookie_secure"    => FALSE,
    // Zabezpeci, ze cookie je pristupne iba prostrednictvom http, nie cez Javascript
    "session.cookie_httponly"  => TRUE
  );

  /***
  * Constructor
  *
  * @param  Void
  * @return Void
  */
  public function __construct()
  {
    // set expiration time (from config.php.ini)
    $configuration['session.gc_maxlifetime'] = Date::getInSec(Config::getArray('DATE')['EXPIR']);
    // set expiration time (from config.php.ini)
    $configuration['session.cookie_lifetime'] = $configuration['session.gc_maxlifetime'];
  }

  /***
  * Start session
  *
  * @param  Void
  * @return Void
  */
  public function launchSession()
  {
    // if launched session?
    if (session_id() == "")	{
      // load config array
      $this->loadConfig();
      // session start
      session_start();
    }
  }

  /***
  * Load config
  *
  * @param  Void
  * @return Void
  */
  private function loadConfig()
  { 
    // loop throught config array
    foreach (self::$configuration as $var => $value)	{
      // is exists?
      if (function_exists('ini_set'))	{
        // init value
        ini_set($var, $value);
      }
    }
  }

  /***
  * Get session id
  *
  * @param  Void
  * @return String - session id
  */
  public function getId()
  {
    return session_id();
  }

  /***
  * Regenerate session id
  *
  * @param  Void
  * @return Void
  */
  private function regenerate()
  {
    session_regenerate_id(true);
  }

  /***
  * Setter $_SESSION
  *
  * @param  String         - key
  * @param  String | Array - value
  * @param  Bool           - regenerate
  * @return Bool
  */
  public static function set($key = false, $value = false, $regenerate = false)
  {
    // check if non empty value
    if (func_num_args() < 2) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too less arguments!"); 
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
    // check if non empty value
    if (empty($value)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Value must be <b>NON</b> empty value!"); 
    }
    // check if key exists in session array
    if (array_key_exists($key, $_SESSION)) {
      // check if array wih key exists
      if (!is_array($_SESSION[$key])) {
        // create array
        $_SESSION[$key] = array($_SESSION[$key], $value);
      // if array with key exists
      } else {
        // store next session
        $_SESSION[$key][] = $value;
      }
    } else {
      // if no present in session array
      $_SESSION[$key] = $value;
    }
    // regenerate?
    if ($regenerate === true)	{
      // regenerate id
      $this->regenerate();
    }
    // success return
    return true;
  }

  /***
  * Getter
  *
  * @param  String - key
  * @return String | Array
  */
  public static function get($key = false, $exception = false)
  {
    // check if 1 argument come
    if (func_num_args() > 2) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Just <b>TWO</b> argument accepted!"); 
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
    if (!array_key_exists($key, $_SESSION)) {
      //
      if ($exception !== false) { 
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: <b>".$key."</b> does not exist in SESSION!");
      //
      } else {
        // return false
        return false;    
      }
    }
    // return session
    return $_SESSION[$key];
  }

  /***
  * Destroy $_SESSION
  *
  * @param  String  - key
  * @param  Boolean - use session_destroy()
  * @return Void
  */
  public static function destroy($key, $destroy = false)
  {
    // check if non empty value
    if (empty($key)) {
      // unset session
      unset($_SESSION); 
    } else {
      // odnastavenie konkretnu session
      unset($_SESSION[$key]);
    }
    // Destroy session?
    if ($destroy === true) {
      // unset all SESSION
      session_unset();
      // destroy SESSION
      session_destroy();
    }
  }
}

