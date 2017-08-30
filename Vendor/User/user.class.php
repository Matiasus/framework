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
  

  /** @var Array */
  private $loggeduser = null;

  /** @var Boolean */
  private $isLoggedIn = false;

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
  * Log user
  *
  * @param  Void
  * @return Void
  */
  public function logOn()
  {
    // user
    $this->loggeduser = null;
    // logged on
    $this->isLoggedIn = false;
    // user stored in session
    $user = Session::get(Config::get('USER', 'NAME'));
    // if user logged on
    if(false !== $user) {
      // SESSION User exists?
      if (is_array($user))	{
        // user
        $this->loggeduser = $user;
        // user login
        $this->isLoggedIn = true;
        // success
        return true;
      }	
    }
    // not logged on
    return false;
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
    if(!empty($user))
    {
      // store user values
      Session::set(self::USER, array(
        self::SESS_LOGIN        => TRUE,
        self::SESS_ID           => $user->Id,
        self::SESS_EMAIL        => $user->Email,
        self::SESS_NAME         => $user->Username,
        self::SESS_PRIVILEGES	=> $user->Privileges,
        self::SESS_REGISTRATION => $user->Registration
      ), True);
      // log on user
      $this->LogOn();
      // success
      return true;
    }
    // unsuccess
    return false;
  }

  /***
   * Nastavenie prihlasenie uzivatela
   *
   * @param Void
   * @return Bool
   */
  public function getLoggedIn()
  {
    return $this->isLoggedIn;
  }

  /**
   * Volanie prihlaseneho uzivatela
   * 
   * @param Void
   * @return Object | null
   */
  public function getLoggedUser()
  {
    return $this->loggeduser;
  }


}
