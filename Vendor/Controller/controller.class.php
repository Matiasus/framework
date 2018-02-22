<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:      Mato Hrinko
* Datum:      07.12.2016 / update
* Adresa:     http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Vendor\Controller;

// use
use \Vendor\Route\Route as Route;

class Controller implements \Vendor\Controller\Icontroller {

  /** @const */
  const RENDER = 'render';
  
  /** @var Variables */
  protected $variables = array();

  /***
   * Constructor
   *
   * @param  Void
   * @return Void
   */
  public function __construct()
  {
  }

  /***
   * Get variables
   *
   * @param  Void
   * @return Void
   */
  public function getVariable($key)
  {
    // check if non empty value
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>1</b> arguments allowed!"); 
    }    
    // check if non empty value
    if (empty($key)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be <b>NON</b> empty value!"); 
    }
    // check if string
    if (!is_string($key)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Key must be a <b>string</b>!"); 
    }
    // check if variable exists
    if (!array_key_exists($key, $this->variables)) {
      // variable not exists
      return false;
    }
    // return variable from storage
    return $this->variables[$key];
  }

  /***
   * Call method
   *
   * @param Void
   * @return Void
   */
  public function callMethod()
  {
    // controller namespace
    $controller = Route::get('controller_namespace');
    // render method
    $method = self::RENDER.ucfirst(Route::get('view'));
    // Doplnenie parametrov do renderovacej metody
    if (!method_exists($controller, $method)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Method <b>'.$method.'</b> in controller <b>'.$controller.'</b> does not exists!');
    }
    // return render method
    return $method;
  }
}

