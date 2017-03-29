<?php

namespace Vendor\Form\Input;
// use
use \Vendor\Config\File as Config;

class Text {
  
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
    // return html code
    $this->html();
    // store to form
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
    $this->html();
    // store to form
    $this->form->storeCode(array($this->params[Config::get('FORM', 'NAME')] => $this->code));
  }

  /***
  * Html code
  * 
  * @param Void
  * @return String
  */
  private function html()
  {
    $this->code  = ($this->params[Config::get('FORM', 'INLINE')] === true) ? "\n\t   <tr><td>" : "\n\t   <label for='id-".strtolower($this->params[Config::get('FORM', 'NAME')])."'>" ;
    $this->code .= $this->params[Config::get('FORM', 'LABEL')].(($this->params[Config::get('FORM', 'REQUIRED')] != '') ? '*' : '');
    $this->code .= (($this->params[Config::get('FORM', 'INLINE')] === true) ? "</td><td>" : "</label><br/>" );
    $this->code .= "\n\t    <input type='".$this->selector."'";
    $this->code .= ($this->params[Config::get('FORM', 'MAXLEN')] !== false) ? " maxlength='".$this->params[Config::get('FORM', 'MAXLEN')]."'" : "";
    $this->code .= " name='".$this->params[Config::get('FORM', 'NAME')]."' id='id-".strtolower($this->params[Config::get('FORM', 'NAME')])."'";
    $this->code .= " value='" . $this->params[Config::get('FORM', 'VALUE')]."' ".$this->params[Config::get('FORM', 'REQUIRED')]."/>";
    $this->code .= (($this->params[Config::get('FORM', 'INLINE')] === true) ? "</td></tr>" : "<br/>");
  }
}
