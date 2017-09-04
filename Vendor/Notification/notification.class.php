<?php 

namespace Vendor\Notification;

// use
use \Vendor\Session\Session as Session,
    \Vendor\Route\Route as Route;

class Notification {

  /** @const */
  const NAME    = "Poznamkovyblog";
  /** @const */
  const FROM    = "From: Poznamkovyblog";
  /** @const */
  const SUBJECT = "Registrácia na poznamkovyblog.cekuj.net";	
  /** @const */
  const URLADDR = "/form/aktivacia";

  /** @var \Vendor\Mailer\Mailer */
  private $mailer;

  /***
   * Constructor
   *
   * @param  Void
   * @return Void
   */
  public function __construct(\Vendor\Mailer\Mailer $mailer)
  {
    // @var \Vendor\Mailer\Mailer
    $this->mailer = $mailer;
  }

  /***
   * Processing
   *
   * @param  Void
   * @return Void
   */
  public function process()
  {
    // check if non empty value
    if (func_num_args() != 3) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much or too low arguments! <b>3</b> arguments allowed!"); 
    }
    // list values
    list($to, $nick, $password) = func_get_args();
    // code 
    $code = hash("sha256", $to.$nick);
    // url address 
    $mvc = Route::get('module').self::URLADDR;
    // build activation link
    $link = Route::getSerNameUri().'/'.$mvc.'/'.$code;
    // email message
    $message  = "Vitajte na ".self::NAME.".cekuj.net,<br/><br/>ďakujeme Vám za registráciu. Zároveň pevne veríme, že sa Vám tu bude páčiť.<br/>";
    $message .= "Účet je potrebné aktivovať kliknutím na následujúci link:<br/>";
    $message .= $link."<br/><br/>";
    $message .= "Meno: ".$nick."<br/>";
    $message .= "Heslo: ".$password."<br/>Emailová adresa: ".$to."<br/><br/>";
    $message .= "Váš tým poznamkovyblog.cekuj.net";
    // email header
    $headers  = "Content-Type: text/html; charset = \"UTF-8\";\n";
    $headers .= "Reply-to: Mato Hrinko <mato.hrinko@gmail.com>\r\n";
    $headers .= "Return-path: Mato Hrinko <mato.hrinko@gmail.com>\r\n";
    $headers .= 'From: '.self::NAME.' <poznamkovyblog@srv4.endora.cz>' . "\r\n";
    $headers .= 'Cc: '.self::NAME.' <poznamkovyblog@srv4.endora.cz>' . "\r\n";
    $headers .= 'Bcc: '.self::NAME.' <poznamkovyblog@srv4.endora.cz>' . "\r\n";

    return array($to, self::SUBJECT, $message, $headers, $code);
  }

  /***
   * Emial notification
   *
   * @param  String
   * @return Void
   */
  public function email($selector, $params = array())
  {
    // if not filled all data
    if (empty($params))	{
      // flash message
      Session::set("flash", "Nevyplnené polia [to, from, message, header]!!!", false);
      // redirect
      Route::redirect("/front/form/registracia");
    }
    // send email
    if (!$this->mailer->send(\Vendor\Mailer\Mailer::MAIL, $params)) {
      // flash message
      Session::set("flash","Email neodoslany!!!", false);
      // redirect
      Route::redirect("/front/form/registracia");	
    }
    // flash message
    Session::set("flash", "Pre dokončenie úspešnej registrácie kliknite na validačný kód odoslaný na Vašu emailovú adresu!", false);
    // redirect
    Route::redirect("");
  }
 }
