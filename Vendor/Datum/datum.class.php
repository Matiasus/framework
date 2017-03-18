<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:		    Mato Hrinko
*	Datum:		    07.12.2016 / update
*	Adresa:		    http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiration: 		
*
***/
namespace Vendor\Datum;

use \Vendor\Config\Config as Config;

class Datum extends \DateTime {
  
  /** @var Object \DateTime Instancia triedy DateTime	*/
  private $instance;

  /** @var String Actual time	*/
  private static $actual_time;

  /** @var Array multiplier */
  private static $multiplier = array(
    "SECS" =>  1, 
    "MINS" => 60, 
    "HOUS" => 60, 
    "DAYS" => 24, 
    "MONS" => 30.417, 
    "YEAS" => 12
  );  

  /***
  * Constructor
  *
  * @param Void
  * @return Void
  */
  public function __construct() 
  {
    // @var \DateTime instance of parrent class
    $this->instance = get_parent_class();
    // actual time
    self::$actual_time = new $this->instance('NOW');
  }

  /***
  * Actual time
  *
  * @param Void
  * @return Void
  */
  public static function getActualTime()
  {
    // return actual time
    return self::$actual_time
               ->format(CONFIG::get('DATUM', 'FORMAT'));
  }

  /***
  * Compare two dates (actual and requested)
  *
  * @param String - date
  * @return Integer 0: actual time = date
  *                 1: actual time < date
  *                -1: actual time > date
  */
  public function difference($date)
  {
    // Create instance with date
    $newDatum = new $this->instance($date);
    // is the same dates
    if ($newDatum == self::$actual_time)	{
      return 0;
    }
    // is younger date
    if ($newDatum > self::$actual_time) {
      return 1;
    }
    // is older date
    if ($newDatum < self::$actual_time) {
      return -1;
    }
  }

  /***
  * Set time in seconds
  *
  * @param Array - date must correspond with multiplier array
  * @return Integer
  */
  public static function getInSec($date = array())
  {
    // value in seconds
    $secs = 0;
    // auxillary calculus
    $temp = 1;
    // loop throught items of multiplier
    foreach (self::$multiplier as $key => $value) {
      // if not exists in config ini
      if (!array_key_exists($key, $date)) {
        // throw to exception with error message
        throw new \Exception("[".get_called_class()."]:[".__LINE__."]:   Does not find parameter <b>'$key'</b> in config file!");
      }
      // auxillary calc
      $temp = $temp * $value;
      // only non null value
      if ($date[$key] != 0) {
        // add to actual value
        $secs = $secs + $date[$key] * $temp;
      } 
    }
    // value in seconds
    return $secs;
  }
}

