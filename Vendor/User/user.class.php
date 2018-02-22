<?php 

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:       Mato Hrinko
* Datum:       16.05.2017 / update
* Adresa:      http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Vendor\User;

// use session class
use \Vendor\Config\File as Config,
    \Vendor\Session\Session as Session;

/** @class User */
class User {

  const USER  = "User";
  const PREG_AGENT = "/[a-zA-Z0-9.\/]+/";

  const SESS_ID  = "Id";
  const SESS_NAME  = "Username";
  const SESS_EMAIL = "Email";
  const SESS_LOGIN = "isLoggedIn";
  const SESS_PRIVILEGES  = "Privileges";
  const SESS_REGISTRATION  = "Registration";
  const SESS_CODEVALIDATION  = "Codevalidation";

  /** @var Objekt \Vendor\Database\Database */
  private $database;

  /***
   * Constructor
   *
   * @param \Vendor\Database\Database
   * @param \Vendor\Authenticate\Authenticate
   * @return Void
   */
  public function __construct(\Vendor\Database\Database $database)
  {
    // @var \Vendor\Database\Database
    $this->database = $database;  
  }

  /***
   * Login user
   *		 
   * @param  Array
   * @return Boolean
   */
  public function store($user)
  {
    // Overenie, ci je vratena hodnota overenia prihlasovacich udajov neprazdne pole 		
    if(!empty($user)) {
      // store user values
      Session::set(self::USER, array(
        self::SESS_LOGIN        => TRUE,
        self::SESS_ID           => $user->Id,
        self::SESS_EMAIL        => $user->Email,
        self::SESS_NAME         => $user->Username,
        self::SESS_PRIVILEGES	  => $user->Privileges,
        self::SESS_REGISTRATION => $user->Registration
      ), True);
      // success
      return true;
    }
    // unsuccess
    return false;
  }

  /***
   * Logoff user
   *		 
   * @param  Array
   * @return Boolean
   */
  public function remove()
  {
    // destroy actual session of logged user
    Session::destroy(self::USER, false);
    // Session regenerate
    Session::regenerate();
  }

  /***
   * Nastavenie prihlasenie uzivatela
   *
   * @param Void
   * @return Bool
   */
  public function getLoggedIn()
  {
    // is user login?
    if ($this->getLoggedUser()) {
      // user is login 
      return true;
    } 
    // user is not login
    return false;
  }

  /**
   * Volanie prihlaseneho uzivatela
   * 
   * @param Void
   * @return Object | null
   */
  public function getLoggedUser()
  {
    // get stored user
    $user = Session::get("User");
    // if user exists
    if (!empty($user)) {
      // success
      return $user;
    }
    // unsuccess - no stored user
    return false;
  }


}
