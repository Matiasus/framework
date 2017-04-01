<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Input {

  /** @var \Vendor\Html\Html */
  private $html = null;

  /** @var \Vendor\Form\Input\Items */
  private $items;

  /** @var parameters */
  private $parameters;

  /** allowable attributes */
  private $input_type_text = array(
    /** input types */
    'name',
    'label',
    'value',
    'id',
    'maxlength'
  );

  /** @var Array */
  private $params = array();


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
   * 
   *
   * @param 
   * @return 
   */
  public function text()
  {
    // check number of arguments
    if (func_num_args() > 5) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // arguments
    $arguments = func_get_args();
    // attributes
    $attributes = array(
      'type' => __FUNCTION__
    );
    // assign values
    while (list($key, $value) = each($arguments)) {
      // assign values
      $attributes[$this->input_type_text[$key]] = $value;
    }
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
   * @return 
   */
  public function email()
  {
    // check number of arguments
    if (func_num_args() > 5) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // arguments
    $arguments = func_get_args();
    // attributes
    $attributes = array(
      'type' => __FUNCTION__
    );
    // assign values
    while (list($key, $value) = each($arguments)) {
      // assign values
      $attributes[$this->input_type_text[$key]] = $value;
    }
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
   * @return 
   */
  public function password()
  {
    // check number of arguments
    if (func_num_args() > 5) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // arguments
    $arguments = func_get_args();
    // attributes
    $attributes = array(
      'type' => __FUNCTION__
    );
    // assign values
    while (list($key, $value) = each($arguments)) {
      // assign values
      $attributes[$this->input_type_text[$key]] = $value;
    }
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
   * @return 
   */
  public function submit()
  {
    // check number of arguments
    if (func_num_args() > 3) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>3</b>!');
    }
    // arguments
    $arguments = func_get_args();
    // attributes
    $attributes = array(
      'type' => __FUNCTION__
    );
    // assign values
    while (list($key, $value) = each($arguments)) {
      // assign values
      $attributes[$this->input_type_text[$key]] = $value;
    }
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
   * @return 
   */
  public function routine($function = false)
  {
    // check number of arguments
    if (func_num_args() > 5) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // arguments
    $arguments = func_get_args();
    // attributes
    $attributes = array(
      'type' => __FUNCTION__
    );
    // assign values
    while (list($key, $value) = each($arguments)) {
      // assign values
      $attributes[$this->input_type_text[$key]] = $value;
    }
    // send attributes
    $this->items->setParameters($this->parameters);
    // send attributes
    $this->items->setAttributes($attributes);
    // @var \Vendor\Form\Input\Items
    return $this->items;
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

