<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Composer {
  
  /** @var String */
  private $tag;
  
  /** @var \Vendor\Html\Html */
  private $html;

  /** @var String */
  private $content;

  /** @var Array */
  private $code = array();
  
  /** @var Array */
  private $attributes = array();

  /** @var Array */
  private $parameters = array();

  /***
   * Constructor
   *
   * @param 
   * @return Void
   */
  public function __construct(\Vendor\Html\Html $html)
  {
    // @var \Vendor\Html\Html
    $this->html = $html;
    // parameters of form
    $this->parameters;
  }
  
  /***
   * 
   *
   * @param 
   * @return 
   */
  public function setTag($tag = false)
  {
    // set parameters
    $this->tag = $tag;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function setParameters($parameters = array())
  {
    // set parameters
    $this->parameters = $parameters;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function setContent($content)
  {
    // set content
    $this->content = $content;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function setAttributes($attributes = array())
  {
    // set attributes
    $this->attributes = $attributes;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function getAttribute($attribute)
  {
    // not empty?
    if (!empty($attribute)) {
      // if exists
      if (array_key_exists($attribute, $this->attributes)) {
        // return value
        return $this->attributes[$attribute];
      }
    }
    // 
    return false;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function getParameter($parameter)
  {
    // if empty
    if (!empty($parameter)) {
      // if exists
      if (array_key_exists($parameter, $this->parameters)) {
        // return value
        return $this->parameters[$parameter];
      }
    }
    return null;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function getCode()
  {
    // get html code
    return $this->code;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function create()
  {
    // name
    $name = $this->getAttribute(Config::get('FORM', 'NAME'));
    // content
    $content = $this->getAttribute(Config::get('FORM', 'LABEL'));
    // name must be set
    if (empty($name)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Composer can\'t store created html code! In config file misses FORM-NAME!');
    }
    // key
    $key = strtolower($name);
    // td element
    $td = $this->build('td', false, $content);
    // remove label from input
    if (array_key_exists(Config::get('FORM', 'LABEL'), $this->attributes)) {
      // remove label
      unset($this->attributes[Config::get('FORM', 'LABEL')]);
    }
    // input element
    $input = $this->build($this->tag, $this->attributes);   
    // inline form
    if ($this->getParameter(Config::get('FORM', 'INLINE')) !== true) {
      // tr element
      $tr  = $this->build('tr', false, $td);
      // td element
      $td  = $this->build('td', false, $input);
      // tr element
      $tr .= $this->build('tr', false, $td);       
    } else {
      // td element
      $tr  = $this->build('td', false, $content);
      // td element
      $td .= $this->build('td', false, $input);
      // tr element
      $tr  = $this->build('tr', false, $td);
    }
    // final code
    $this->code[$key] = $tr."\n";
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  private function build($tag = false, $attributes = false, $content = false)
  {
    // build html tag
    return $this->html->tag($tag)
      ->attributes($attributes)
      ->content($content)  
      ->create();
  }
}
