<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Inputs {

  /** @var \Vendor\Html\Html */
  private $html = null;

  /** @var errors */
  private $errors = array();

  /** @var String */
  private $code = "";

  /** @var parameters */
  private $parameters;

  /** @var Array */
  private $attributes = array();

  /** allowable types */
  public $allowable = array(
    /** input types */
    'button',
    'checkbox',
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
    'type',
    'name',
    'label',
    'value',
    'id',
    'maxlength',
    'placeholder',
    'required',
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
  public function __construct(\Vendor\Html\Html $html, $attributes = array())
  {
    // @var \Vendor\Html\Html
    $this->html = $html;
    // save attributes
    $this->attributes = $attributes;
  }
  
 /***
  * @desc   Create inputs in form
  *
  * @param  Array
  *
  * @return Void
  */
  public function content($query = array())
  {
    // check number of arguments
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>1</b>!');
    }

    // init value
    $tag = "";
    $content = "";
    $attributes = array();

    // check if value is array
    if (is_array($query) && !empty($query)) {
      // loop
      foreach ($query as $key => $value) {
        // trim string
        $tag = $key = trim($key);
        // if dash was found
        if ( strpos($key, "-") !== false ) {
          // get tag
          $tag = substr($key, 0, strpos($key, "-"));
        }
        // check if non empty array
        if (is_array($value) && !empty($value)) {
          // loop thorugh array
          foreach ($value as $item_key => $item_value) {
            // array if attributes
            if ( is_array($item_value) ) {
              // array of attributes
              $attributes = $item_value;
/*
              if ( !empty($item_value) ) {
                foreach ( $item_value as $type_key => $type_value ) {
                  if ( in_array($type_key, $this->input_type_attr) ) {
                    $attributes[$type_key] = $type_value;
                  } else {
                    $this->errors[] = $type_key;
                  }
                }
              }
*/
            // content if string
            } else {
              // string content
              $content = $item_value;
            }
          }
        }

      // create code
      $this->code .= "\n\t\t  <tr><td>" . $this->html->tag($tag)
        ->attributes($attributes)
        ->content($content)
        ->create() . "</td></tr>";
      }
    }
  }

  /***
   * @desc   Getter errors
   *
   * @param  Array
   *
   * @return Array / True
   */
  public function getErrors()
  {
    // check errors
    if ( !empty($this->errors) ) {
      // error exists 
      return $this->errors;
    }
    // without errors
    return true;
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
    // row elements
    $code = $this->code;
    // table element
    $code = "\n\t\t " . $this->html->tag('table')
      ->attributes(array('id'=>'table'))
      ->content($code. "\n\t\t ")
      ->create();
    // create form
    $code = "\n\t\t" . $this->html->tag('form')
      ->attributes($this->attributes)
      ->content($code. "\n\t\t")
      ->create() . "\n";
    // return html code
    return $code;
  }
}

