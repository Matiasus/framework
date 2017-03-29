<?php

namespace Vendor\Form\Input;
// use
use \Vendor\Config\File as Config;

class Items {
  
  /** @var \Vendor\Form\Form */
  private $form;

  /** @var String */
  private $code;

  /** @var Array */
  private $params = array();

  /***
  * Constructor
  * 
  * @param Array
  * @return String
  */
  public function __construct(\Vendor\Form\Form $form, 
                              $params = array(),
                              $selector)
  {
    // @var \Vendor\Form\Form
    $this->form = $form;
    // parameters
    $this->params = $params;
    // get selector for text, email, 
    $this->selector = $selector;
    // create html code
    $this->createHtmlCode();
    // store to form codes
    $this->form->storeCode(array($this->params[Config::get('FORM', 'NAME')] => $this->code));
  }

  /***
  * 
  *
  * @param 
  * @return 
  */
  public function required()
  {
    // required
    $this->params[Config::get('FORM', 'REQUIRED')] = Config::get('FORM', 'REQUIRED');
    // return html code
    $this->createHtmlCode();
    // store to form
    $this->form->storeCode(array($this->params[Config::get('FORM', 'NAME')] => $this->code));
  }

  /***
  * Html code
  * 
  * @param Void
  * @return String
  */
  private function createHtmlCode()
  {
    $this->code  = ($this->params[Config::get('FORM', 'INLINE')] === true) ? "\n\t   <tr><td>" : "\n\t   <label for='id-".strtolower($this->params[Config::get('FORM', 'NAME')])."'>" ;
    $this->code .= $this->params[Config::get('FORM', 'LABEL')].(($this->params[Config::get('FORM', 'REQUIRED')] != '') ? '*' : '');
    $this->code .= (($this->params[Config::get('FORM', 'INLINE')] === true) ? "</td><td>" : "</label><br/>" );
    $this->code .= "\n\t    <input type='".$this->selector."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'ID')])) ? "" : " id='id-".strtolower($this->params[Config::get('FORM', 'NAME')])."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'ROWS')])) ? "" : " rows='".$this->params[Config::get('FORM', 'ROWS')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'COLS')])) ? "" : " cols='".$this->params[Config::get('FORM', 'COLS')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'VALUE')])) ? "" : " value='".$this->params[Config::get('FORM', 'VALUE')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'CLASS')])) ? "" : " class='".$this->params[Config::get('FORM', 'CLASS')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'MAXLEN')])) ? "" : " maxlength='".$this->params[Config::get('FORM', 'MAXLEN')]."'";
    // check if no empty
    if (!empty($this->params[Config::get('FORM', 'PARAMS')])) {
      // check if array
      if (is_array($this->params[Config::get('FORM', 'PARAMS')])) {
        // loop through options
        foreach ($this->params[Config::get('FORM', 'PARAMS')] as $key => $value) {
          // append supplement options
          $this->code .= " ".$key."='".$value."' "; 
        }
      }
    }   
    $this->code .= "/>";
    $this->code .= (($this->params[Config::get('FORM', 'INLINE')] === true) ? "</td></tr>" : "<br/>");
  }
}
