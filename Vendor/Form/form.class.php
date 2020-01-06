<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Form {

  /** @const dash */
  const DASH = "-";

  /** @var \Vendor\Form\Inputs */
  private $inputs = null;

  /** @var \Vendor\Html\Html */
  private $html = null;

  /** @var Array */
  private $attributes = array();

  /** @var Array */
  private $params = array();

  /** @var Array */
  private $data = array();

  /** @var String */
  private $code = "";

  /***
   * @desc   Constructor
   *
   * @param  \Vendor\Html\Html
   *
   * @return Void
   */
  public function __construct(\Vendor\Html\Html $html)
  {
    // @var \Vendor\Html\Html
    $this->html = $html;
  }

 /***
  * @desc   Create inputs in form
  *
  * @param  Array
  *
  * @return Void
  */
  public function attrs($attributes = array())
  {
    // save attributes
    $this->attributes = $attributes;
    // @var \Vendor\Form\Inputs
    $this->inputs = new \Vendor\Form\Inputs($this->html, $this->attributes);
    // return \Vendor\Form\Inputs
    return $this->inputs;
  }

  /***
   * @desc   Get html code
   *
   * @param  Void
   *
   * @return String
   */
  public function getCode()
  {
    // return html code
    return $this->inputs->getCode();
  }

  /***
   * @desc   Get data
   *
   * @param  Void
   * @return String
   */
  public function getData()
  {
    return $this->data;
  }

  /***
   * @desc   Check if name of form element corresponds with column in table of DB
   *
   * @param  Void
   * @return Bool
   */
  public function succeedSend(\Vendor\Database\Database $database, $table)
  {
    if (isset($_POST) && !empty($_POST)) {
      // loop through POSTs
      foreach($_POST as $key => $value)	{
        // store value
        $this->data[$key] = $value;
        // check if column in database exists
        $database->columnExists($key, $table);
      }
      // return true
      return true;
    }
  }
}
