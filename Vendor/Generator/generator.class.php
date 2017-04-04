<?php 

namespace Vendor\Generator;

class Generator{

  /** @const */
  const IDENTITY	= "Identificator";
  /** @const */
  const ADDRESS 	= "Address";
  /** @const */
  const ACCEPT 		= "Accept";
  /** @const */
  const AGENT			= "Agent";
  /** @const */
  const USER 			= "User";

  /** @const */
  const TOKEN_DELIMITER = ":";

  /** @const */
  const PATTERN_USER_AGENT = "/[a-zA-Z0-9.\/]+/";

  /** @var Array */
  private static $indices = array(
    self::ADDRESS => "REMOTE_ADDR",
	  self::ACCEPT  => "HTTP_ACCEPT", 
    self::AGENT   => "HTTP_USER_AGENT"
  );

  /** @var Token */		
  private $token;

  /** @var Array \Vendor\User\User->getLoggedUser()	*/		
  private $user;

  /***
  * Konstruktor
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
  * Vytvorenie tokenu pre trvale prihlasenie
  *
  * @param Void
  * @return Void
  */
  public function create()
  {
    // Spracovanie glabalneho pola $_SERVER s pozadovanymi exponentmi
    foreach (self::$indices as $key => $value) {
      // zapis hodnot z globalnej premennej $_SERVER
      $token[$key] = (!empty($_SERVER[$value])) ? $_SERVER[$value] : "";
    }
    // @Array Rozklad user agenta
    preg_match_all(self::PATTERN_USER_AGENT, $token[self::AGENT], $result);

    // @Array Rozklad user agenta do pola
    if (is_array($result) && !empty($result))	{
      // prechod prvkami
      foreach($result[0] as $key => $value)	{
        // zapis do pola
        $agent[] = $value;
      }
      // @String Spojenie user do retazca agenta bez medzier
      $token[self::AGENT] = implode("\0", $agent);
      // @String Spojenie s akceptaciou, ip a id prihlaseneho uzivatela
      $this->token = implode(self::TOKEN_DELIMITER, $token);
      // Zahashovanie tokenu pomocou md5 */
      return $this->token = md5($this->token);
    }
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
}
