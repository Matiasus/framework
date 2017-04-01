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

class Composer {

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

  /** @var String */
  private $tag;

  /** @var String */
  private $content;

  /** @var String */
  private $html_code;

  /** @var String of attributes */
  private $attributes;
      
  /***
   * Constructor
   *
   * @param  
   * @return Void
   */
  public function __construct ()
  {
  }

  /***
   * Setter of tag
   *
   * @param  String
   * @return Void
   */
  public function setTag ($tag)
  {
    if (!is_string($tag)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Tag must be a <b>string</b>!");       
    }
    // set tag
    $this->tag = $tag;
  }

  /***
   * Setter of attributes
   *
   * @param  String
   * @return Void
   */
  public function setAttributes ($attributes)
  {
    if (!is_string($attributes)) {
      // no attributes
      $this->attributes = false;       
    } else {
      // set attributes
      $this->attributes = $attributes;
    }
  }
  
  /***
   * Setter of content
   *
   * @param  String
   * @return Void
   */
  public function setContent ($content)
  {
    if (is_string($content)) {
      // set content
      $this->content = $content;      
    }
  }

  /***
   * Compose html code of given tag
   *
   * @param  Void
   * @return Void
   */
  public function create ()
  {
    // init html code
    $this->html_code = '<'.$this->tag;
    // if set close tags?
    if (!empty(self::$self_close_tags)) {
      // loop throuh tags
      foreach (self::$self_close_tags as &$self_close_tag) {
        // compare with created tag
        if (strcmp($self_close_tag, $this->tag) === 0) {
          // close tag
          $this->html_code .= $this->attributes." />";
          // end loop
          return $this->html_code;
        }
      }
      // unset variable
      unset($self_close_tag);
      // append html code
      return $this->html_code .= $this->attributes.">".$this->content."</$this->tag>";
    }
  }
}
