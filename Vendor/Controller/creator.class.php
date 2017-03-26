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

class Creator {

  /** @const */
  const MODEL       = "Model";
  /** @const */
  const MODULE      = "Module";
  /** @const */
  const CONTROLLER  = "Controller";
  /** @const */
  const APPLICATION = "Application";

  /** @var */
  private $container;

  /** @var */
  private $instance;

  /** @var */
  private $route;

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
   * @param Object \Vendor\Di\Container
   * @return Void
   */
  public function __construct(\Vendor\Di\Container $container)
  {
    // @var \Vendor\Di\Container
    $this->container = $container;
    // @var \Vendor\Route\Route
    $this->route = $this->container
                        ->service('\Vendor\Route\Route');
  }

  /***
   * Create controller
   *
   * @param Void
   * @return Void
   */
  public function controller()
  {
    // controller namespace
    $controller = $this->route->get('controller_namespace');
    // create instance of controller according to url
    $this->container->store($controller);
    // save to variable
    $this->instance = $this->container->service($controller);
    // Render method
    $this->rendername = 'render'.ucfirst($this->route->get('view'));
    // Doplnenie parametrov do renderovacej metody
    if (!method_exists($this->instance, $this->rendername)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Method '.$this->rendername.' in '.$this->fullname.' does not exists!');
    }
    // cookie process - safe actual url address
    $this->setCookies();
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
  private function setCookies()
  {
    // get name from config file
    $agent = Config::get('COOKIES', 'AGENT');
    // get name from config file
    $sesid = Config::get('COOKIES', 'SESID');
    // actual url
    $acturi = $this->container
                   ->service('\Vendor\Route\Route')
                   ->getFullUri(true);
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

