<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Input {

  /** @var \Vendor\Form\Form */
  private $form;

  /** @var Array */
  private $params = array();

  /***
  * Constructor
  *
  * @param Void
  * @return Void
  */
  public function __construct(\Vendor\Form\Form $form)
  {
    // @var \Vendor\Form\Form
    $this->form = $form;
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function text($name = false, $label = false, $value = false, $id = false, $maxlen = false)
  {
    // check number of arguments
    if (func_num_args() > 5) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // check number of arguments
    $this->preprocess($name, $label, $value, $id, $maxlen);
    // @var \Vendor\Form\Input\Text
    return new \Vendor\Form\Input\Items($this->form, $this->params, 'text');
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function email($name = false, $label = false, $value = false, $id = false, $maxlen = false)
  {
    // check number of arguments
    if (func_num_args() > 5) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // check number of arguments
    $this->preprocess($name, $label, $value, $id, $maxlen);
    // @var \Vendor\Form\Input\Text
    return new \Vendor\Form\Input\Items($this->form, $this->params, 'email');
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function password($name = false, $label = false, $value = false, $id = false, $maxlen = false)
  {
    // check number of arguments
    if (func_num_args() > 5) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>5</b>!');
    }
    // check number of arguments
    $this->preprocess($name, $label, $value, $id, $maxlen);
    // @var \Vendor\Form\Input\Text
    return new \Vendor\Form\Input\Items($this->form, $this->params, 'password');
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function submit($name = false, $value = false, $id = false, $params = array())
  {
    // check number of arguments
    if (func_num_args() > 4) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>4</b>!');
    }
    // check number of arguments
    $this->preprocess($name, false, $value, false, $id, false, false, false, $params);
    // @var \Vendor\Form\Input\Text
    return new \Vendor\Form\Input\Items($this->form, $this->params, 'submit');
  }

  /***
   * 
   *
   * @param 
   * @return 
   */
  public function preprocess($name = false, $label = false, $value = false, $id = false, $maxlen = false, $class = false, $rows = false, $cols = false, $params = array())
  {
    // parameters for input
    $this->params = array(
      Config::get('FORM', 'REQUIRED') => false,
      Config::get('FORM', 'INLINE')   => $this->form->getInline(),
      Config::get('FORM', 'ID')       => $id,
      Config::get('FORM', 'NAME')     => $name,
      Config::get('FORM', 'LABEL')    => $label,
      Config::get('FORM', 'VALUE')    => $value,
      Config::get('FORM', 'MAXLEN')   => $maxlen,
      Config::get('FORM', 'CLASS')    => $class,
      Config::get('FORM', 'ROWS')     => $rows,
      Config::get('FORM', 'COLS')     => $cols,
      Config::get('FORM', 'PARAMS')   => $params,
    );
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

