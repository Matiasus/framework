<?php 
/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:          Mato Hrinko
* Datum:          07.12.2016 / update
* Adresa:         http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Vendor\Generator;

class Generator{

  /** @const */
  const TOKEN_DELIMITER = ":";
  /** @const */
  const IDENTITY = "Identificator";
  /** @const */
  const ADDRESS = "Address";
  /** @const */
  const ACCEPT = "Accept";
  /** @const */
  const AGENT = "Agent";
  /** @const */
  const USER = "User";

  /** @var Token */
  private $token;

  /** @var Array \Vendor\User\User->getLoggedUser() */		
  private $user;
	
  /** @var Array */
  private static $indices = array(
    self::ADDRESS => "REMOTE_ADDR",
    self::ACCEPT  => "HTTP_ACCEPT", 
    self::AGENT   => "HTTP_USER_AGENT"
  );

  /***
  * Constructor
  *
  * @param \Vendor\User\User
  * @return Void
  */
  public function __construct(\Vendor\User\User $user)
  {
    // @var Object \Vendor\User\User->getLoggedUser()
    $this->user = $user->getLoggedUser();
  }
    
  /***
  * Create token
  *
  * @param Void
  * @return Void
  */
  public function create()
  {
    // init value
    $token = array();
    // loop server values
    foreach (self::$indices as $key => $value) {
      // write into array
      $token[$key] = (!empty($_SERVER[$value])) ? $_SERVER[$value] : "";
    }
    // implode items of saved array
    $this->token = implode(self::TOKEN_DELIMITER, $token);
    // hash token
    return $this->token = md5($this->token);
  }
}
