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

  /** @var \Vendor\Html\Html $html */
  private $html;
  
  /** @var \Vendor\Html\Content $content */
  private $content;  
      
  /***
   * Constructor
   *
   * @param  \Vendor\Html\Html $html
   * @return Void
   */
  public function __construct (\Vendor\Html\Html $html)
  {
    // @var \Vendor\Html\Html
    $this->html = $html;
    // @var \Vendor\Html\Content
    $this->content = new \Vendor\Html\Content($this->html);    
  }
  
  /***
   * 
   *
   * @param  Array
   * @return Void
   */
  public function setAttrs ($attributes = array())
  {
    // init attributes
    $tag_attributes = '';
    // check if non empty attributes
    if(!empty($attributes) && 
        isset($attributes)) 
    {
      // check all elements of array
      while (list($key, $value) = each($attributes)) {
        // check if value is not array
        if (!is_string($value) && 
            !is_int($value)) 
        {
          // throw to exception with error message
          throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Value can\'t be array!");         
        }
        // if string
        if (is_string($value)) {
          // append attribute
          $tag_attributes .= ' '.$key.'=\''.$value.'\'';
        // if integer
        } elseif (is_int($value)) {
          // append attribute
          $tag_attributes .= ' '.$key.'='.$value.'';
        }        
      }
      // reset pointer
      reset($attributes);
      // set attributes
      $this->html->setAttrs($tag_attributes);
      // return 
      return $this->content;
    }
  }  
}
