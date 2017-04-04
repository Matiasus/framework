<?php
/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:        Mato Hrinko
* Datum:        30.03.2017
* Adresa:       http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description:
*
***/

namespace Vendor\Html;

class Attributes {

  /** @var \Vendor\Html\Content */
  private $content;

  /** @var \Vendor\Html\Composer */
  private $composer;

  /** @var String of attributes */
  private $attributes; 

  /** @var String of tag */
  private $tag;
      
  /***
   * Constructor
   *
   * @param  String
   * @return Void
   */
  public function __construct ($tag)
  {
    // store tag
    $this->tag = $tag;
    // @var \Vendor\Html\Composer
    $this->composer = new \Vendor\Html\Composer();
    // @var \Vendor\Html\Content
    $this->content = new \Vendor\Html\Content($this->composer);
  }
  
  /***
   * 
   *
   * @param  Array
   * @return Void
   */
  public function attributes ($attributes = array())
  {
    // init attributes
    $this->attributes = '';
    // check if non empty attributes
    if(empty($attributes)) 
    {
      // no attributes
      $this->attributes = false;
    } else {
      // check all elements of array
      while (list($key, $value) = each($attributes)) {
        // check if value is not array
        if (!is_string($value) && 
            !is_bool($value) &&
            !is_int($value)) 
        {
          // throw to exception with error message
          throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Value can\'t must be string or integer or boolean!");         
        }
        // if string
        if (is_string($value)) {
          // without empty
          if (!empty($value)) {
            // append attribute
            $this->attributes .= ' '.$key.'=\''.$value.'\'';
          }
        // if integer
        } elseif (is_int($value)) {
          // append attribute
          $this->attributes .= ' '.$key.'='.$value.'';
        // if bool
        } elseif (is_bool($value) && 
                  $value === true) {
          // append attribute
          $this->attributes .= ' '.$key.'';
        }        
      }
      // reset pointer
      reset($attributes);
    }
    // set tag
    $this->composer->setTag($this->tag);
    // set attributes
    $this->composer->setAttributes($this->attributes);
    // return 
    return $this->content;
  }
   
  /***
   * Compose html code of given tag
   *
   * @param  Void
   * @return Void
   */
  public function create ()
  {
    // set tag
    $this->composer->setTag($this->tag);
    // compose html code
    return $this->composer->create();
  }
}
