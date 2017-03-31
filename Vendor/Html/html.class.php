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
  private static $html_code;
  
  /** @var String */
  private static $tag_content; 
  
  /** @var String */
  private static $tag_attributes;
  
  /** @var array of self closing tags */
  private static $self_close_tags = array (
    'area',
    'base',
    'br',
    'col',
    'command',
    'embed',
    'hr',
    'img',
    'input',
    'keygen',
    'link',
    'meta',
    'param',
    'source',
    'track',
    'wbr',
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
   * @param  Array
   * @return Void
   */
  public static function setAttrs ($attributes = array())
  {
    // init attributes
    $attributes = '';
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
          $attributes .= ' '.$key.'=\''.$value.'\'';
        // if integer
        } elseif (is_int($value)) {
          // append attribute
          $attributes .= ' '.$key.'='.$value.'';
        }        
      }
      // reset pointer
      reset($attributes);
      // set attributes
      self::$tag_attributes = $attributes;
    }
  }
  
  /***
   * 
   *
   * @param  Void
   * @return Void
   */
  public static function getAttrs ()
  {
    // return string form of attributes
    return self::$attributes;
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
    if (!is_string($tag)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Tag must be a <b>string</b>!"); 
    }
    // save tag
    self::$tag = $tag;
    // compose html tag
    self::composer();
  }
   
  /***
   * Compose html code of given tag
   *
   * @param  Void
   * @return Void
   */
  private static function composer ()
  {
    // init html code
    self::$html_code = '<'.self::$tag;
    // if set close tags?
    if (empty(self::$self_close_tags)) {
      // loop throuh tags
      foreach (self::$self_close_tags as &$self_close_tag) {
        // compare with created tag
        if (strcmp($self_close_tag, self::$tag) === 0) {
          // close tag
          self::$html_code .= ''.self::$getAttributes().' />';
          // end loop
          return true;
        }
      }
      // unset variable
      unset($self_close_tag);
      // append html code
      self::$html_code .= ' '.self::$getAttributes().'>'.self::$tag_content.'</'.self::$tag.'>';
      // success return
      return true;
    }
  }
}
