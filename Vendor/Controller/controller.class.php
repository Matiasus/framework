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
use \Vendor\Cookie\Cookie as Cookie,
    \Vendor\Config\File as Config,
    \Vendor\Date\Date as Date;

class Controller {

  /** @const */
  const MODEL       = "Model";
  /** @const */
  const MODULE      = "Module";
  /** @const */
  const BACKSLASH   = "\\";
  /** @const */
  const CONTROLLER  = "Controller";
  /** @const */
  const APPLICATION = "Application";

  /** @var Object - instance of given controller */
  private $instance;
  
  /** @var */
  private $user;

  /** @var */
  private $route;

  /** @var */
  private $database;
  
  /** @var */
  private $view;

  /** @var */
  private $module;
  
  /** @var */
  private $controller;

  /** @var */
  private $process;

  /** @var */
  private $fullname;

  /** @var */
  private $rendername;

  /** @var Array - array of exception */
  private $exceptions = array('form', 'activate');

  /***
   * Constructor
   *
   * @param Object \Vendor\Container\Container
   * @return Void
   */
  public function __construct(\Vendor\User\User $user,
                              \Vendor\Route\Route $route,
                              \Vendor\Database\Database $database)
  {
    // @var \Vendor\User\User
    $this->user = $user;
    // @var \Vendor\Route\Route 
    $this->route = $route;    
    // @var \Vendor\Database\Database 
    $this->database = $database;

    // set module
    $this->module = $this->route->get('module');
    // init for module
    if ($this->module === false) {
      // module
      $this->module = Config::get('CONTROLLER', 'MODULE');
    }

    // set controller
    $this->controller = $this->route->get('controller');
    // init for controller
    if ($this->controller === false) {      
      // controller
      $this->controller = Config::get('CONTROLLER', 'CONTROLLER');
    }

    // set view
    $this->view = $this->route->get('view');
    // init for view
    if ($this->view === false) { 
      // view
      $this->view = Config::get('CONTROLLER', 'VIEW');      
    }
  }

  /***
   * Create controller
   *
   * @param Void
   * @return Void
   */
  public function create()
  {
    // controller name
    $this->name = $this->controller.self::CONTROLLER;

    // controller full name from parsed url address without Module section
    $this->fullname = '\\'.self::APPLICATION.
                      '\\'.self::CONTROLLER.
                      '\\'.$this->name;
    // module exists?
    $module_exists = strpos(strtolower(Config::get('ROUTE', 'PATTERN')), strtolower(self::MODULE));
    // controller full name from parsed url address with Module section
    if ($module_exists !== false) {
      //  controller name from parsed url address with Module section
      $this->fullname = '\\'.self::APPLICATION.
                        '\\'.self::MODULE.
                        '\\'.ucfirst($this->module).
                        '\\'.self::CONTROLLER.
                        '\\'.$this->name;
    }
  
    // Check if class exists
    if (!class_exists($this->fullname)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Controller <b>".$this->fullname."</b> not exists!");
    }
    // Create instance of given controller according to url
    $this->instance = new $this->fullname();
    // Render method
    $this->rendername = 'render'.ucfirst($this->view);
    // Doplnenie parametrov do renderovacej metody
    if (!method_exists($this->instance, $this->rendername)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Method '.$this->rendername.' in '.$this->fullname.' does not exists!');
    }
    // cookie process - safe actual url address
    $this->processCookies();
    // volanie renderovacej metody
    $this->instance->{$this->rendername}();
/*
    // if is set processing
    if (null !== $route->getProcessing()) {
      // Vytvorenie novej instancie Processing a zavolanie funkcie spracovania podla parametra $_GET['do'] 
      $this->processing = '\Vendor\Processing\Processing';
    }
*/
  }

  /***
  * Cookie process
  *
  * @param  Void
  * @return Void
  */
  private function processCookies()
  {
    // get name from config file
    $agent = Config::get('COOKIES', 'AGENT');
    // get name from config file
    $sesid = Config::get('COOKIES', 'SESID');
    // actual url
    $acturi = $this->route->getFullUrI(true);
    // if exists
    if (!empty($agent)  && 
        !empty($sesid))
    { 
      // Save actual url
      Cookie::set(Config::get('COOKIES', 'LAST_URI'), 
                  $acturi, 
                  time() + Date::getInSec(Config::getArray('DATE')['EXPIR']));
    }
  }

  /***
   * Instance of controller
   *
   * @param Void
   * @return Object | Boolean
   */
  public function getInstance()
  {
    if (!empty($this->instance)) {
      // controller object
      return $this->instance;
    }
    // unsuccess false
    return false;
  }

  /***
   * Controller name
   *
   * @param Void
   * @return String | Boolean 
   */
  public function getName()
  {
    if (!empty($this->name)) {
      // controller name
      return $this->name;
    }
    // unsuccess return 
    return false;
  }
  
  /***
  * Controller full name
  *
  * @param Void
  * @return String | Boolean 
  */
  public function getFullName()
  {
    if (!empty($this->fullname)) {
      // controller name
      return $this->fullname;
    }
    // unsuccess return 
    return false;
  }
}

