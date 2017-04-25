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
namespace Application\Module\Front\Model;

// use
use \Vendor\Session\Session as Session,
    \Vendor\Cookie\Cookie as Cookie,
    \Vendor\Config\File as Config,
    \Vendor\Route\Route as Route,
    \Vendor\Date\Date as Date;

/** @class formproccess */
class Model {

  /** @var Object \Vendor\User\User */
  private $user;

  /** @var Object \Vendor\Database\Database */
  private $database;

  /** @var Object \Vendor\Generator\Generator */
  private $generator;

  /***
  * Constructor
  *
  * @param  
  * @return Void
  */
  public function __construct(\Vendor\User\User $user, 
                              \Vendor\Database\Database $database,
                              \Vendor\Generator\Generator $generator)
  {
    // @var \Vendor\User\User
    $this->user = $user;
    // @var \Vendor\Database\Database
    $this->database = $database;
    // @var \Vendor\Generator\Generator
    $this->generator = $generator;
  }

  /***
  * Overenie platnosti tokenu
  * 
  * @param Void
  * @return String - token
  */
  public function autoLogon()
  {
    // default value
    $user = null;
    // Vytvorenie tokenu
    $token = $this->generator->create();
    // token agenta
    $token_agent = Cookie::get(Config::get('COOKIES', 'AGENT'));
    // token session id
    $token_sessionid = Cookie::get(Config::get('COOKIES', 'SESID'));
    // overi existenciu COOKIES
    if (!empty($token_agent) && 
        !empty($token_sessionid))	
    {
      // Porovnanie tokena v $_COOKIE s vygenerovanym tokenom
      if (strcmp($token_agent, $token) === 0) {
        // Dotaz na uzivatela
        // ~~~~~~~~~~~~~~~~~~
        // vyber vsetko
        $select = array('*');
        // z tabulky Articles
        $from = array(Config::get('MYSQL', 'TB_AUT'));
        // podla zhody id s parametrom v url
        $where = array(
          array('=', Config::get('MYSQL', 'TB_AUT').'.Session'=>$token_sessionid)
        );
        // Overenie existencie tokena v databaze
        $item = $this->database
                     ->select($select)
                     ->from($from) 
                     ->where($where)
                     ->query();
        // Ak zaznam s tokenom existuje
        if ($item !== false) {
          // Porovnanie zhody tokena a id uzivatela v databaze s $_COOKIE
          if (strcmp($token_agent, $item[0]->Token) === 0) {	
            // Overenie, ci nie expirovany datum a cas
            if ($this->datum
                     ->difference($item[0]->Expires))
            {
              // Overenie existencie tokena v databaze
              // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              // vyber vsetko
              $select = array('*');
              // z tabulky Articles
              $from = array(Config::get('MYSQL', 'TB_USE'));
              // podla zhody id s parametrom v url
              $where = array(
                array('=', Config::get('MYSQL', 'TB_USE').'.Id'=>$item[0]->Usersid)
              );
              // Overenie existencie tokena v databaze
              $answer = $this->database
                             ->select($select)
                             ->from($from) 
                             ->where($where)
                             ->query();
              // prihlaseny uzivatel
              $user = $answer[0];
            }
          }
        }
      }
    }
    // get last visit page
    $uri = Cookie::get(Config::get('COOKIES','LAST_URI'));
    // check if user log on
    if (null !== $user) {
      // redirect to last visited uri
      $this->route
           ->redirect($uri);
    }
    // return false
    return false;
  }

  /***
  * 
  * 
  * @param  \Vendor\Form\Form
  * @return Void
  */
  public function showFormPrihlasenie(\Vendor\Form\Form $form)
  {
    // set method
    $form->setMethod(Config::get('FORM', 'METHOD_POST'));
    // set action
    $form->setAction(Route::getfullUri(true));
    // set form
    $form->setInline(true);
    // input text field
    $form->input()
         ->text('Username', 'Meno/Name')
         ->html5Attrs('required')
         ->create();
    // input password field
    $form->input()
         ->password('Passwordname', 'Heslo/Pasword')
         ->html5Attrs('required')
         ->create();
    // input checkbox field
    $form->input()
         ->checkbox('Persistentlog', 'Trvalé prihlásenie', 'Pamataj')
         ->create();
    // submit
    $form->input()
         ->submit('Prihlasenie', '', 'Prihlásenie')
         ->create();
    // check if created columns exist in database
    if ($form->succeedSend($this->database, Config::get('ICONNECTION', 'MYSQL', 'T_USER'))) {
        // process form
        $this->logon($form);
    }
    // return code
    return $form;
  }

 /***
  * 
  * 
  * @param  \Vendor\Form\Form
  * @return Void
  */
  public function showFormRegistracia(\Vendor\Form\Form $form)
  {
    // set method
    $form->setMethod(Config::get('FORM', 'METHOD_POST'));
    // set action
    $form->setAction(Route::getfullUri(true));
    // set form
    $form->setInline(true);
    // input email
    $form->input()
         ->email('Email', 'E-mail')
         ->html5Attrs('required')
         ->create();
    // input text field
    $form->input()
         ->text('Username', 'Meno/Name')
         ->html5Attrs('required')
         ->create();
    // input password field
    $form->input()
         ->password('Passwordname', 'Heslo/Pasword')
         ->html5Attrs('required')
         ->create();
    // submit
    $form->input()
         ->submit('Registracia', '', 'Registrácia')
         ->create();
    // check if created columns exist in database
    if ($form->succeedSend($this->database, Config::get('ICONNECTION', 'MYSQL', 'T_USER'))) {
      // callback logon
      $this->registration($form);
    }
    // return code
    return $form;
  }

  /***
  * Registracia po uspesnej kontrole nazvov stlpcov
  * 
  * @param Formular
  * @Return Void
  */
  public function registration($form)
  {
    // Extrahovanie dat z formulara
    $user = $form->geData();

    if (empty($user["Email"]) || 
        empty($user["Username"]) || 
        empty($user["Passwordname"]))	
    {

    }
    // Notifikacia, posielanie emailov s tokenom pre uspesnu registraciu
    $notification = new \Vendor\Notification\Notification($this);
    /***
    * Predspracovanie udajov pre odoslanie emailom
    * @Parameters: Sting, String, String
    *   1.@parameter = Komu
    *   2.@parameter = Meno/Nick
    *   3.@parameter = Heslo/Password
    *
    * @return: Array
    *   0.@return => Komu
    *   1.@return => Predmet
    *   2.@return => Sprava
    *   3.@return => Od koho
    *   4.@return => Validacny kod
    */
    $parameters = $notification->Preprocessing($user["Email"], $user["Username"], $user["Passwordname"]);

    // Hash hesla
    $user["Passwordname"] = $this->user->hashpassword($user["Passwordname"]);
    // Pridanie na koniec pola validacny kod
    $user["Codevalidation"] = $parameters[4];
    // Overenie existencie uzivatela v databaze na zaklde emailu
    $check = $this->database->select(
      array("*"), 
      array("Email"=>$user["Email"]), 
      \Application\Config\Settings::$Detail->Mysql->Table_Users
    );
    // exists?
    if ($check === FALSE)	{
      // Vlozenie uzivatela do databazy
      $this->database->insert(
        $user,  
        \Application\Config\Settings::$Detail->Mysql->Table_Users
      );
      // Odoslanie emailu podla predspracovanych udajov
      $notification->Email($parameters);
    }	else {
      // flash sprava
      Session::set("flash", "Užívateľ so zadaným emailom už existuje !!!", false);
      // presmerovanie
      Route::redirect("/front/form/registracia");
    }
  }

  /***
  * Spracovanie prihlasovacieho formulara
  * 
  * @param \Vendor\Vendor\Form
  * @return Void
  */
  public function logon($form)
  {
    // Odoslane data
    $data = $form->getData();
    // Trvale prihlasenie
    $persistent = false;
    // Overenie, ci je poziadavka na trvale prihlasenie
    if (isset($_POST['Persistentlog'])) { 
      // persistent login
      $persistent = true;	
    }
    // Poziadavka / dotaz
    $select = array("*");
    // odkial
    $from = array(
      Config::get('ICONNECTION', 'MYSQL', 'T_USER')
    );
    // podmienka
    $where = array(
      array(
        '=', Config::get('ICONNECTION', 'MYSQL', 'T_USER').'.Username'=>$data['Username']), 
      'AND', 
      array(
        '=', Config::get('ICONNECTION', 'MYSQL', 'T_USER').'.Passwordname'=>$this->user->hashpassword($data['Passwordname'])),
      'AND',
      array(
        '=', Config::get('ICONNECTION', 'MYSQL', 'T_USER').'.Validation'=>'valid')
    );

    // poziadavaka na overenie uzivatela
    $query_select = array($select, $from, $where);
    // Overenie prihlasovacich udajov
    if ($this->user->login($query_select, $persistent)) {
      // logged user
      $logged_user = $this->user->getLoggedUser();
      // privileges
      $privileges = $logged_user['Privileges'];
      // insert data
      $this->database
           ->insert(array(
              'Id_Users'   => $logged_user['Id'],
              'Datum'     => date("Y-m-d H:i:s"), 
              'Ip_address' => $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'], 
              'User_agent' => $_SERVER['HTTP_USER_AGENT'] 
             ), 
            Config::get('ICONNECTION', 'MYSQL', 'T_LOG'),
            true);
      // redirect
      Route::redirect($privileges . "/home/default");
    } else {
      // flash message
      Session::set("flash", "Nesprávne meno, heslo alebo neaktívnosť účtu !!!", false);
      // redirect
      Route::redirect("");
    }
  }

  /***
  * Aktivacia na zaklade url tokenu
  * 
  * @param Void
  * @return Void
  */
  public function activation()
  {
    $parameters = Route::getParameters();

    if (!empty($parameters[0])) {
      $user = $this->database->select(array("*"), 
                                      array("Codevalidation"=>$parameters[0]), 
                                      Config::get('MYSQL', 'TB_USE'));
      // Overenie, ci podla validacneho kluca existuje iba jeden zaznam v tabulke Users 
      if (count($user) == 1) {
        // Update z invalid na valid
        $this->database->update(array("Validation"=>"valid"), 
                                array("Codevalidation"=>$parameters[0]), 
                                \Application\Config\Settings::$Detail->Mysql->Table_Users);
        // Vypis flash spravy
        Session::set("flash", "Váš účet bol úspešne aktivovaný, pokračujte prosím prihlásením!", false);
        // Presmerovanie na prihlasovaciu stranku
        Route::redirect("");
      }
    }
  }
}

