<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Form {

  /** @var Object \Vendor\Form\Input */
  private $input = null;

  /** @var Array */
  private $variables = array();
  
  /** @var Array */
  private $params = array();

  /** @var String */
  private $code = '';

  /***
  * Constructor
  *
  * @param 
  * @return Void
  */
  public function __construct()
  {
    // @var \Vendor\Form\Input
    $this->input = new \Vendor\Form\Input($this);
  }
  
 /***
  * Get \Vendor\Form\Input
  *
  * @param  Void
  * @return \Vendor\Form\Input
  */
  public function input()
  {
    // return \Vendor\Form\Input
    return $this->input;
  }

  /***
  * Set action type 
  *
  * @param String
  * @return void
  */
  public function setAction($action, $processing = false) 
  {
    // check if processing required
    if($processing === false) {
      // set action
      $this->params[Config::get('FORM', 'ACTION')] = $action;
    } else {
      // set action with processing
      $this->params[Config::get('FORM', 'ACTION')] = $action."?".Config::get('ROUTE', 'PROCESS')."=".$processing;
    }
  }

  /***
  * Get action
  *
  * @param Void
  * @return String
  */
  public function getAction()
  { 
    // check if set action
    if (!empty($this->params[Config::get('FORM', 'ACTION')])) {
      // return set action
      return $this->params[Config::get('FORM', 'ACTION')];
    }
    // action not set
    return false;
  }

  /***
  * Set type of method
  *
  * @param String GET || POST (default)
  * @return Void
  */
  public function setMethod($method)
  {
    // check if method exists
    if (strcmp(strtolower($method), strtolower(Config::get('FORM', 'METHOD_GET'))) !== 0 &&
        strcmp(strtolower($method), strtolower(Config::get('FORM', 'METHOD_POST'))) !== 0)
    {
      // error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Chosen form method <b>\''.$method.'\'</b> not exists!');
    }
    // set method
    $this->params[Config::get('FORM', 'METHOD')] = strtoupper($method);
  }

  /***
  * Get type of method
  *
  * @param  Void
  * @return String GET || POST (default)
  */
  public function getMethod()
  { 
    // check if set method
    if (!empty($this->params[Config::get('FORM', 'METHOD')])) {
      // return set method
      return $this->params[Config::get('FORM', 'METHOD')];
    }
    // method not set
    return false;
  }

  /***
  * Set inline form
  *
  * @param Bool 
  * @return Void
  */
  public function setInline($inline)
  {
    // check if method exists
    if (!is_bool($inline)) {
      // error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Inline form <b>must</b> be boolean!');
    }
    // set inline form
    $this->params[Config::get('FORM', 'INLINE')] = $inline;
  }

  /***
  * Get type of inline method
  *
  * @param Void
  * @return Bool
  */
  public function getInline()
  { 
    // check if set inline method
    if (!empty($this->params[Config::get('FORM', 'INLINE')])) {
      // return set inline method
      return $this->params[Config::get('FORM', 'INLINE')];
    }
    // method not set
    return false;
  }

  /***
  * Set inline form
  *
  * @param Bool 
  * @return Void
  */
  public function setId($id)
  {
    // check if method exists
    if (empty($id)) {
      // error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Id <b>must</b> be non empty value!');
    }
    // check if method exists
    if (!is_string($id)) {
      // error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Id <b>must</b> be string!');
    }
    // set inline form
    $this->params[Config::get('FORM', 'ID')] = $id;
  }

  /***
  * Get type of inline method
  *
  * @param Void
  * @return Bool
  */
  public function getId()
  { 
    // check if set inline method
    if (!empty($this->params[Config::get('FORM', 'ID')])) {
      // return set inline method
      return $this->params[Config::get('FORM', 'ID')];
    }
    // method not set
    return false;
  }

  /***
  * Store html codes
  *
  * @param  Array
  * @return Void
  */
  public function storeCode($code = array())
  {
    // check if non empty
    if (empty($code)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Arguments must be non empty array!');
    }
    // check if non empty
    if (count($code) > 1) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Array must be one dimensional array!');
    }
    // store html code
    $this->code[array_keys($code)[0]] = array_values($code)[0];
  }

  /***
  * Get html code
  *
  * @param Void
  * @return Void
  */
  public function getCode()
  {
    // form tag
    $code  = "\n\t<form action='".$this->params[Config::get('FORM', 'ACTION')];
    $code .= "' method='".$this->params[Config::get('FORM', 'METHOD')]."'";
    $code .= (isset($this->params[Config::get('FORM', 'ID')])) ? " id='".$this->getId()."'>" : ">";
    // table tag
    $code .= (($this->params[Config::get('FORM', 'INLINE')] === true ) ? "\n\t  <table id='table".$this->getId()."'>":"");
    // append all stored html codes
    foreach ($this->code as &$html_code) {
      // appending codes
      $code .= $html_code;
    }
    // unset
    unset($html_code);
    // end table tag
    $code .= (($this->params[Config::get('FORM', 'INLINE')] === true ) ? "\n\t  </table>" : "");
    // end form tag
    $code .= "\n\t</form>";
    // return code
    return $code;
  }

  /***
  *
  *
  * @param void
  * @return Bool
  */		
  public function succeedSend()
  {
    if (isset($_POST) && 
        !empty($_POST))
    {
      // overenie posielanych dat
      $this->validation = call_user_func(array($this, "validation"));
      // navratova hodnota
      return $this->validation;
    }
  }

  /***
  * 
  *
  * @param Void
  * @return Bool
  */		
  private function validation($allerrors = false)
  {
    // inicializacia chybovej hlasky
    $this->registry->errors->sql = "";
    // Prechod jednotlivych prvkov odoslanych metodou POST
    foreach($_POST as $key => $value)	{
      // Overenie existencie nayvu stlpca v MySQL tabulke databazy
      if ($this->registry->mysql->existenceOfColumn($key) !== TRUE)
      {
        // Osetrenie submitu
        if (!empty($this->submit->name)) {
          if (strcmp($key, $this->submit->name) === 0) {
            continue;
          }
        }
        // Osetrenie checkboxu
        if (!empty($this->checkbox->name)) {
          if (strcmp($key, $this->checkbox->name) === 0) {
            continue;
          }
        }
      } else {
        // Zapis udajov do pola data bez submit hodnoty
        $this->data[$key] = $value;
      }
    }
    // return true
    return true;
  }
}
