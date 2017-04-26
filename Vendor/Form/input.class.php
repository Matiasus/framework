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
  private $input_type_attr = array(
    /** input types attributes */
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
    // process routine for every type
    return $this->routine(func_get_args(), __FUNCTION__);
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
    // process routine for every type
    return $this->routine(func_get_args(), __FUNCTION__);
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
    // process routine for every type
    return $this->routine(func_get_args(), __FUNCTION__);
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function checkbox()
  {
    // check number of arguments
    if (func_num_args() > 3) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // arguments
    $arguments = func_get_args();
    // process routine for every type
    return $this->routine(func_get_args(), __FUNCTION__);
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
    // process routine for every type
    return $this->routine(func_get_args(), __FUNCTION__);
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function routine($arguments = array(), $type)
  {
    $attributes = array();
    // check number of arguments
    if (func_num_args() > 2) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>2</b>!');
    }
    // attributes
    $attributes = array(
      'type' => $type
    );
    // assign values
    while (list($key, $value) = each($arguments)) {
      // check if attribute exists
      if (!array_key_exists($key, $this->input_type_attr)) {
        // throw to exception with error message
        throw new \Exception('['.get_called_class().']:['.__LINE__.']: Try to assign value which are not defined in \$input_type_attr!');
      }
      // assign values
      $attributes[$this->input_type_attr[$key]] = $value;
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

