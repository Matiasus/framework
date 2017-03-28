<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Form {

  /** @const */
	const GET = 'get';
  /** @const */
	const POST = 'post';
  /** @const */
	const REQUIRED = true;
  /** @const */
  const UNREQUIRED = false;

	/** @var Object \Vendor\Form\Input */
  private $input = null;

  /** @var String */
  private $code = '';

  /** @var Array */
  private $params = array();

  /** @var Array */
	private $variables = array();

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
	 * 
	 *
	 * @param 
	 * @return Void
	 */
	public function input()
  {
    // return input
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
			$this->params['action'] = $action;
		} else {
      // set action with processing
			$this->params['action'] = $action."?do=".$processing;
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
    if (!empty($this->params['action'])) {
      // return set action
      return $this->params['action'];
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
		if (strcmp(strtolower($method), self::POST) !== 0 &&
        strcmp(strtolower($method), self::GET)  !== 0)
		{
      // error message
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Chosen form method <b>\''.$method.'\'</b> not exists!');
    }
    // set method
    $this->params['method'] = strtoupper($method);
	}

	/***
	 * Get type of method
   *
	 * @param Void
	 * @return String GET || POST (default)
	 */
	public function getMethod()
  { 
    // check if set method
    if (!empty($this->params['method'])) {
      // return set method
      return $this->params['method'];
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
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Inline form <b>\''.$inline.'\'</b> not boolean!');
    }
    // set inline form
		$this->params['inline'] = $inline;
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
    if (!empty($this->params['inline'])) {
      // return set inline method
      return $this->params['inline'];
    }
    // method not set
    return false;
  }

	/***
	 * 
   *
	 * @param  String
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
		$code  = "\n\t<form action='".$this->params[Config::get('FORM', 'ACTION')]."' method='".$this->params[Config::get('FORM', 'METHOD')]."' id=''>";
    // table tag
		$code .= (($this->params[Config::get('FORM', 'INLINE')] === false ) ? "\n\t  <table class=''>":"");
		// append all stored html code
		foreach ($this->code as &$html_code) {
      // appending
      $code .= $html_code;
    }
    // unset
    unset($html_code);
    // end table tag
		$code .= (($this->params[Config::get('FORM', 'INLINE')] === false ) ? "\n\t  </table>" : "");
    // end form tag
		$code .= "\n\t</form>";
    // return code
		return $code;
	}

	/***
	 * Funkcia vracajuca obsah, ktory ma byt nahradeny v sablone {formular meno formulara}
   *
	 * @param String - id formulara
	 * @return String - html, ktory bude nahradeny {formular meno_formulara}
	 */
	public function getFormContent( $id = false )
  {
		$this->id = $id;
		$form = $this->create();

		return $form;
	}

	/***
	 * Validacia zadanych nazvov jednotlivych prvkov formulara
	 * ci sa zhoduju s nazvami stlpcov prislusnej tabulky
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
	 * Overuje, ci sa nastavene $_POSTy zhoduju s nazvami stlpcov v tabulke
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
				// Vypis flash spravy
				$this->registry
						 ->session
						 ->set("flash", "Stlpec <strong>" . $key . "</strong> v tabulke <b>" . $this->registry->mysql->getTable() . "</b> neexistuje!<br/>", false);
				// Ak najde stlpec, ktory sa v tabulke nenachadza vratti FALSE
				return FALSE;
			}
			else
			{
				// Zapis udajov do pola data bez submit hodnoty
				$this->data[$key] = $value;
			}
		}

		return TRUE;
	}

	/***
	 * Ziskanie dat
   *
	 * @param Void
	 * @return Array
	 */	
	public function getData()
  {
		return $this->data;
	}

}
