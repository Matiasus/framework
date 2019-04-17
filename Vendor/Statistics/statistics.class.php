<?php

/***
 * POZNAMKOVYBLOG Copyright (c) 2015 
 * 
 * Autor:        Mato Hrinko
 * Datum:        04.03.2018
 * Adresa:       http://poznamkovyblog.cekuj.net 
 * 
 * ------------------------------------------------------------
 *
 ***/
namespace Vendor\Statistics;

use \Vendor\Cookie\Cookie as Cookie,
    \Vendor\Config\File as Config,
    \Vendor\Route\Route as Route,
    \Vendor\Date\Date as Date;

class Statistics {

  /** @var Object \Vendor\User\User */
  private $user;

  /** @var Object \Vendor\Database\Database */
  private $database;

  /** @vra String table statistics */ 
  private $table;

  /***
   * @desc    Constructor
   *
   * @param   Void
   * @return  Void
   */
  public function __construct(\Vendor\User\User $user, \Vendor\Database\Database $database)
  {
    // @var \Vendor\User\User
    $this->user = $user;
    // @var \Vendor\Database\Database
    $this->database = $database;
    // statistics
    $this->table_statistics = Config::get('ICONNECTION', 'MYSQL', 'T_VISIT');
  }

  /***
   * @desc   
   *
   * @param  Void
   * @return Void
   */
  public function store()
  {
    // if user is not logged in
    if (!empty($user = $this->user->getLoggedUser())) {
      // visited page
      $page = Route::getReqUri();
      // insert data
      $insert = array(
        "Sessionid_Logins" => session_id(),  
        "Page"      => $page,
        "Arrival"   => date("Y-m-d H:i:s"),
      );
      // insert data
      $this->database->insert($insert, $this->table_statistics);
      // store session id into cookie
      Cookie::set(Config::get('COOKIES', 'LAST_URI'), 
        $page,
        time() + Date::getInSec(Config::getArray('DATE')['EXPIR'])
      );
    }
  }

}
