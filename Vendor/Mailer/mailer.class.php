<?php 

namespace Vendor\Mailer;

use \Vendor\Session\Session as Session;

class Mailer {

	const MAIL  = "Mail";
	const OAUTH = "Oauth";

  /***
   * Constructor
   *
   * @param  Void
   * @return Void
   */
	public function __construct()
	{
	}

	/***
	 * Send mail
	 *
	 * @param  String
   * @param  Array
	 * @return Bool
	 */
	public function send ($selector, $params = array())
	{
    if (empty($params)) {
      // unsuccess
      return false;
    }
    // expect 4 parameters = to, subject, message, header
    if (count($params) != 4) {
      // unsuccess
      return false;
    }
    // list parameters
    list($to, $subject, $message, $header) = $params;
		// select
		switch ($selector) {
			// by mail()
			case self::MAIL:
        // mail function php
				if (!mail($to, $subject, $message, $header)) {
          // unsuccess
					return false;
				}
				break;

			// by Google Authentification & Authorisation
			case self::OAUTH:
        // oauth google mail
				if (!$this->oauth()) {
          // unsuccess 
					return false;
				}
				break;
		}
    // success
		return true;
	}

	/***
	** Odosielanie mailom prostrednictvom Oauth 2.0
	**
	** @parameter Void
	** @return Bool
	*/
	private function oauth()
	{
		/***
		** Zavolanie autoloadera
		*/
		require_once ROOT_DIR . 'Library/Google/autoload.php';

		/***
		** Definovanie parametrov pre request po uspesnosti ktoreho by sa mal vratit kod
		*/
		$params  = array(	
				 	 				"client_secret" => "FmR1W0RvbB2U10NcyM1_jE3d",
									"grant_type" 		=> "refresh_token",
									"refresh_token"	=> "1/fs0gsglO22TpUyTRhKI_rE__23x8sGUtyRlH0CT6HEVIgOrJDtdun6zK6XiATCKT",
									"client_id" 		=> "114168583640-9nhe2mngm8fgfrd54baoq2uvmc42hm6p.apps.googleusercontent.com",
									"access_type"		=> "offline",
									"scopes"				=> array(\Google_Service_Gmail::MAIL_GOOGLE_COM)
		);

		$client = new \Google_Client();

		$client->setAccessType($params['access_type']);
		$client->setClientId($params['client_id']);
		$client->setClientSecret($params['client_secret']);
		$client->refreshToken($params['refresh_token']);

		if ($client->getAccessToken())
		{
			/* Nastavenie access tokenu */
			$client->setAccessToken($client->getAccessToken());

			/* Volanie sluzby Gmail	*/
			$service = new \Google_Service_Gmail($client);

			/* Obsahovy typ text/html, znakova sada utf8 */
			$mime  = "Content-Type: text/html; charset=UTF-8\n";
			/* Komu */
			$mime .= "to: ". $this->to ."\n";
			/* Od koho */
			$mime .= 'from: Poznamkovyblog <Poznamkovyblog@srv4.endora.cz>' . "\n";
			/* Predmet osetreny na znakovu sadu utf8 */
			$mime .= "subject: =?utf8?q?" . utf8_decode($this->subject) . "?=\n\n";
			/* Sprava */
			$mime .= $this->message;

			/* Kodovanie prostrednictvom base64 a osetrenie znakov */
			$mime = base64_encode($mime);
			/* Nahradenie znaku '+/' znakom '-_' */
			$mime = strtr($mime, '+/', '-_');
			/* Orezanie posledneho znaku '=' */
			$mime = rtrim($mime, '=');

			$mesg = new \Google_Service_Gmail_Message();
			/* Surova sprava aj s hlavickou */
			$mesg->setRaw($mime);

			/***
			** Odoslanie spravy 
			** @var me - autentifkovana osoba
			** @var base64(String) - kodovana sprava base64
			*/
			$service->users_messages->send("me", $mesg);

			return True;

		}

		return False;
	}

}
