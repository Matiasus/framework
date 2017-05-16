<?php 

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:      Mato Hrinko
*	Datum:      07.12.2016 / update
*	Adresa:     http://poznamkovyblog.cekuj.net
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
  const SESS_LOGON  = "Logon";
  const SESS_PRIVILEGES  = "Privileges";
  const SESS_CODEVALIDATION  = "Codevalidation";

  /** @var Objekt \Vendor\Database\Database	*/
  private $database;

  /** @var Array Detaily, popis uzivatela */
  private $loggeduser = null;

  /** @var Boolean Prihlasenost uzvatela	*/
  private $isLoggedIn = false;

  /***
  * Constructor
  *
  * @param \Vendor\Database\Database
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
      // ak existuje SESSION uzivatela
      if (is_array($user))	{
        // uzivatel
        $this->loggeduser = $user;
        // uzivatel prihlasny
        $this->isLoggedIn = true;
        // success
        return true;
      }	
    }
    // not logged on
    return false;
  }

  /***
  * Prihlasenie uzivatela prostrednictvom triedy Authenticate
  *		 
  * @param  String - nick a heslo
  * @return Boolean
  */
  public function login($user = array(), $persistent = false)
  {
    // Autentifikacna trieda
    $authenticate = new \Vendor\Authenticate\Authenticate($this->database);
    $allUserData  = $authenticate->checkLogin($user);
    // Overenie, ci je vratena hodnota overenia prihlasovacich udajov neprazdne pole 		
    if(is_array($allUserData) && 
       !empty($allUserData))
    {
      // ****************************************************************
      // NUTNOST PREROBIT LOGIKU CEZ SESSSION ID
      // ****************************************************************
      // Nastavenie prihlasenia uzivatela
      Session::set(self::USER, array(
        self::SESS_LOGIN	    => TRUE,
        self::SESS_ID           => $allUserData[0]->Id,
        self::SESS_EMAIL	    => $allUserData[0]->Email,
        self::SESS_NAME	        => $allUserData[0]->Username,
        self::SESS_PRIVILEGES	=> $allUserData[0]->Privileges,
        self::SESS_LOGON	    => $allUserData[0]->Logon
      ), True);

      $this->LogOn();

      // Poziadavka na trvale prihlasovanie
      if ($persistent === true) {
        // 
        $authenticate->createPersistentLogin();
      }
      return true;
    }
    //
    return false;
  }

  /**
  * Hash
  *
  * @param  String 
  * @return String 
  */
  public function hashpassword($password)
  {
    return hash("sha256", $password);
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
