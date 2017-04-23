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

class Controller  implements \Vendor\Controller\Icontroller {

  /** @const */
  const RENDER = 'render';

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

