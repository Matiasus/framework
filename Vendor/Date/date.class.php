<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:		    Mato Hrinko
* Datum:		    07.12.2016 / update
* Adresa:		    http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiration: 		
*
***/
namespace Vendor\Date;

use \Vendor\Config\File as Config;

class Date extends \DateTime {
  
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
    // actual time
    self::$actual_time = new \DateTime('NOW');
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
               ->format(CONFIG::get('DATE', 'FORMAT'));
  }
  
  /***
   * Future time
   * 
   * @param Array
   * @return String
   */
  public static function getFutureTime($date = array())
  {
    // time
    $date = new \DateTime('+'.self::getInSec($date).' seconds');
    // return future date in format
    return $date->format(CONFIG::get('DATE', 'FORMAT'));
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
    $date = new \DateTime($date);
    // is the same dates
    if ($date == self::$actual_time) {
      // the same
      return 0;
    }
    // is younger date
    if ($date > self::$actual_time) {
      // early
      return 1;
    }
    // is older date
    if ($date < self::$actual_time) {
      // late
      return -1;
    }
  }
}

