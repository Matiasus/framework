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

class Html {
  
  /** @var String */
  private $html_code;
  
  /** @var String */
  private $tag_content; 
  
  /** @var String */
  private $tag_attributes;
  
  /** @var array of self closing tags */
  private $self_close_tags = array (
    'area'
    'base'
    'br'
    'col'
    'command'
    'embed'
    'hr'
    'img'
    'input'
    'keygen'
    'link'
    'meta'
    'param'
    'source'
    'track'
    'wbr'
  );
    
  /***
   * Constructor
   *
   * @param  Void
   * @return Void
   */
  public function __construct ()
  {
  }
  
  /***
   * 
   *
   * @param  Void
   * @return Void
   */
  public function create ($tag = false)
  {
    // check if non empty value
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>1</b> argument allowed!"); 
    }    
    // check if non empty value
    if (empty($tag)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Tag must be <b>NON</b> empty!"); 
    }
    // check if string
    if (!is_string($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Tag must be a <b>string</b>!"); 
    }
    // save tag
    $this->tag = $tag;
  }
  
  /***
   * Compose html code of given tag
   *
   * @param  Void
   * @return Void
   */
  public function composer ()
  {
    // init html code
    $this->html_code = '<'.$this->tag = $tag;
    // if set close tags?
    if (empty($this->self_close_tags)) {
      // loop throuh tags
      foreach ($this->self_close_tags as &$self_close_tag) {
        // compare with created tag
        if (strcmp($self_close_tag, $this->tag) === 0) {
          // close tag
          $this->html_code .= ''.$this->getAttributes().' />';
          // end loop
          return true;
        }
      }
      // unset variable
      unset($self_close_tag);
      // append html code
      $this->html_code .= ' '.$this->getAttributes().'>'.$this->tag_content.'</'.$this->tag.'>';
      // success return
      return true;
    }
  }
  
  /***
   * 
   *
   * @param  Array
   * @return Void
   */
  public function setAttributes ($attributes = array())
  {
    // check if non empty attributes
    if(!empty($attributes) && 
        isset($attributes)) 
    {
      // set attributes
      $this->attributes = $attributes;
    }
  }
  
  /***
   * 
   *
   * @param  Void
   * @return Void
   */
  public function getAttributes ()
  {
    $attributes = '';
    // check if non empty attributes
    if(!empty($this->tag_attributes) && 
        isset($this->tag_attributes)) 
    {
      // must be array
      if (is_array($this->tag_attributes)) {
        // loop through attributes
        foreach ($this->tag_attributes as $key => $value) {
          // if string
          if (is_string($value)) {
            // append attribute
            $attributes .= ' '.$key.'=\''.$value.'\'';
          // if integer
          } elseif (is_int($value)) {
            // append attribute
            $attributes .= ' '.$key.'='.$value.'';
          }
        }
      }
    }
    // return string form of attributes
    return $attributes;
  }
}
