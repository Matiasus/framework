<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Input {

  /** @var \Vendor\Html\Html */
  private $html = null;

  /** @var \Vendor\Form\Input\Items */
  private $items;

  /** @var type */
  private $type;

  /** @var parameters */
  private $parameters;

  /** allowable types */
  public $allowable = array(
    /** input types */
    'button',
    'chceckbox',
    'color',
    'date',
    'datetime-local',
    'email',
    'file',
    'hidden',
    'image',
    'month',
    'number',
    'password',
    'radio',
    'range',
    'reset',
    'search',
    'submit',
    'tel',
    'text',
    'time',
    'url',
    'week'
  );

  /** allowable attributes */
  private $input_type_attr = array(
    /** input types attributes */
    'name',
    'label',
    'value',
    'id',
    'maxlength',
    'min',
    'max',
    'step',
    'onclick',
    'onload'
  );

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
    // @var \Vendor\Form\Input\Items
    $this->items = new \Vendor\Form\Input\Items($this->html);
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
  public function setType($type = false)
  {
    // set parameters
    $this->type = $type;
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
  public function attr()
  {
    // check number of arguments
    if (func_num_args() > count($this->input_type_attr)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>'.count($this->input_type_attr).'</b>!');
    }
    // if array, take first element
    if ((func_num_args() === 0) || !is_array(func_get_arg(0))) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Arguments must be array!');
    }
    // process routine for every type
    return $this->routine(func_get_args()[0]);
  }

  /***
   * 
   *
   * @param
   * 
   * @return 
   */
  public function routine($arguments = array())
  {
    $attributes = array();
    // check number of arguments
    if (func_num_args() > 2) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>2</b>!');
    }
    // only typed inputs
    if ($this->type !== false) {
      // attributes
      $attributes = array(
        'type' => $this->type
      );
    }
    // assign values
    foreach ($arguments as $key => $value) {
    //while (list($key, $value) = each($arguments)) {
      // check if attribute exists
      if (!in_array($key, $this->input_type_attr)) {
        // throw to exception with error message
        throw new \Exception('['.get_called_class().']:['.__LINE__.']: Try to assign value which are not defined in \$input_type_attr!');
      }
      // assign values
      $attributes[$key] = $value;
    }
    // send tag
    $this->items->setTag($this->tag);
    // send attributes
    $this->items->setParameters($this->parameters);
    // send attributes
    $this->items->setAttributes($attributes);
    // @var \Vendor\Form\Input\Items
    return $this->items;
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
    $code = '';
    // array
    $codes = $this->items->getCode();
    // check if no empty
    if (!empty($codes)) {
      // check if array
      if (is_array($codes)) {
        // loop thruogh codes
        foreach ($codes as $item) {
          // html code as string
          $code .= $item;
        }
        // return code as string
        return $code;
      }
    }
    // unsuccess
    return false;
  }

  /***
  * Destructor
  *
  * @param 
  * @return Void
  */
  public function __destruct()
  {
    $this->form = null;
  }
}

