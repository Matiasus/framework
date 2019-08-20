<?php

namespace Vendor\Form\Input;


class Items {
  
  /** @var \Vendor\Form\Composer */
  private $composer;

  /** @var Array */
  private $attributes;

  /** @var Array */
  private $parameters;

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
    // @var \Vendor\Form\Composer
    $this->composer = new \Vendor\Form\Composer($this->html);
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
    // set parameters
    $this->composer->setTag($this->tag);
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
    // set parameters
    $this->composer->setParameters($this->parameters);
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
    // set attributes
    $this->composer->setAttributes($this->attributes);
  }

  /***
  * Html code
  * 
  * @param  
  * @return String
  */
  public function html5Attrs()
  {
    // check if non empty
    if (!empty(func_get_args()) && is_array(func_get_args())) {
      // loop through arguments
      foreach (func_get_args() as $attribute) {
        // check if no array
        if (is_string($attribute)) {
          // without re-writing existing values
          if (!array_key_exists($attribute, $this->attributes)) {
            // append html5 attribute
            $this->attributes[$attribute] = true;
          }
        }
      }
    }
    // set params
    $this->composer->setParameters($this->parameters);
    // set attributes
    $this->composer->setAttributes($this->attributes);
    // \Vendor\Form\Composer
    return $this->composer;
  }

  /***
   * 
   *
   * @param
   *
   * @return 
   */
  public function getCode()
  {
    // set parameters
    return $this->composer->getCode();
  }

  /***
   * 
   *
   * @param
   *
   * @return 
   */
  public function create()
  {
    // input element
    return $this->composer->create();
  }
}
