<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:          Mato Hrinko
* Datum:          04.03.2018 / update
* Adresa:         http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Application\Model;

// use
use \Vendor\Session\Session as Session,
    \Vendor\Cookie\Cookie as Cookie,
    \Vendor\Buffer\Buffer as Buffer,
    \Vendor\Config\File as Config,
    \Vendor\Route\Route as Route,
    \Vendor\Date\Date as Date;

/** @class Model */
class Model {

  /** @var Object \Vendor\User\User */
  private $user;

  /** @var Object \Vendor\Database\Database */
  private $database;

  /** @var String Logins table */
  private $table_logins;
  
  /***
   * Constructor
   *
   * @param  \Vendor\User\User
   * @param  \Vendor\Database\Database
   *
   * @return Void
   */
  public function __construct(\Vendor\User\User $user, \Vendor\Database\Database $database)
  {
    // @var \Vendor\User\User
    $this->user = $user;
    // @var \Vendor\Database\Database
    $this->database = $database;
    // table logins
    $this->table_logins = Config::get('ICONNECTION', 'MYSQL', 'T_LOG');
  }

  /***
   * @desc    Logoff user
   * 
   * @param   Void
   *
   * @return  Void
   */
  public function logoff()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // update values
    $update = array(
      "Logoff" => date("Y-m-d H:i:s")
    );
    // Update udajov do databazy
    $this->database->update(
      $update, 
      array("Sessionid" => session_id()),
      $this->table_logins
    );
    // destroy user session
    $this->user->remove();
    // redirect
    Route::redirect("");
  }
 }

