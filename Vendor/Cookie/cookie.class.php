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
namespace Vendor\Cookie;

class Cookie{

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
	 * Set cookie
	 *
	 * @param String - meno cookie
	 * @param String - hodnota cookie
	 * @param String - doba platnosti cookie
	 * @return Void | False
	 */
	public static function set($name, $value, $expire, $path = "/", $domain = false)
	{
    // check if no empty
    if (empty($name)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]:  Cookie must assign <b>name</b>!");
    }
    // set COOKIE
		setcookie($name, $value, $expire, $path, $domain);
	}

	/***
	 * Volanie premennej ulozenej v poli $_COOKIE
   *
	 * @param String - kluc
	 * @return String | Array | False
	 */
	public static function get($key = false, $exception = false)
  {
    // check if 2 arguments come
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
    if (!array_key_exists($key, $_COOKIE)) {
      // exception?
      if ($exception !== false) {
        // throw to exception with error message      
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]: <b>".$key."</b> does not exist in COOKIE!");
      } else {
        // not exists
        return false;
      }
    }
    // return COOKIE
    return $_COOKIE[$key];
	}

	/***
	 * Zrusenie $_COOKIE
	 *
	 * @param String - meno cookie
	 * @return Void | False
	 */
	public static function destroy($name)
	{
    // check if non empty value
    if (empty($name)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Name must be <b>NON</b> empty value!"); 
    } 
    // unset COOKIE
		unset($_COOKIE[$name]);
    // null COOKIE
		setcookie($name, null, -1, '/');
	}

}

