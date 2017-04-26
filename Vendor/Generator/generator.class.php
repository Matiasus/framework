<?php 

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
  
  /**
   * 
   *
   * @param Void
   * @return Void
   */
  private function validate()
  {
    $token = $this->create();
  }
    
  /***
  * Create token
  *
  * @param Void
  * @return Void
  */
  private function create()
  {
    // Spracovanie glabalneho pola $_SERVER s pozadovanymi exponentmi
    foreach (self::$indices as $key => $value) {
      // zapis hodnot z globalnej premennej $_SERVER
      $token[$key] = (!empty($_SERVER[$value])) ? $_SERVER[$value] : "";
    }
    // @Array explode agent
    preg_match_all(self::PATTERN_USER_AGENT, $token[self::AGENT], $result);
    // check if no empty
    if (is_array($result) && 
        !empty($result))	
    {
      // loop
      foreach($result[0] as $key => $value)	{
        // save to array
        $agent[] = $value;
      }
      // join into string without empty space
      $token[self::AGENT] = implode("\0", $agent);
      // implode items of saved array
      $this->token = implode(self::TOKEN_DELIMITER, $token);
      // hash token
      return $this->token = md5($this->token);
    }
  }
}
