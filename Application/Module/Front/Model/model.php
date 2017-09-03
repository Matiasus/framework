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
 
   /** @const  */
  const EMAIL    = 'Email';
  /** @const  */
  const USERNAME = 'Username';
  /** @const  */
  const PASSNAME = 'Passwordname';
  /** @const  */
  const VALIDATE = 'Validation';  
  
  /** @var Object \Vendor\Database\Database */
  private $database;

  /** @var Object \Vendor\Generator\Generator */
  private $generator;
  
  /** @var Object \Vendor\Notification\Notification */
  private $notificator; 

  /** @var Object \Vendor\Authenticate\Authenticate */
  private $authenticator;    
  
  /***
   * Constructor
   *
   * @param  
   * @return Void
   */
  public function __construct(
    \Vendor\Database\Database $database,
    \Vendor\Generator\Generator $generator,
    \Vendor\Notification\Notification $notificator,
    \Vendor\Authenticate\Authenticate $authenticator)
  {
    // @var \Vendor\Database\Database
    $this->database = $database;
    // @var \Vendor\Generator\Generator
    $this->generator = $generator;
    // @var \Vendor\Notification\Notification
    $this->notificator = $notificator; 
    // @var \Vendor\Authenticate\Authenticate
    $this->authenticator = $authenticator;   
  }

  /***
   * Session login
   * 
   * @param  Void
   * @return String
   */
  public function sessionLogin()
  {
    // token session id
    $uri = Cookie::get(Config::get('COOKIES', 'LAST_URI'));    
    // token session id
    $sessid = Cookie::get(Config::get('COOKIES', 'SESID'));
    // overi existenciu COOKIES
    if (!empty($sessid)) {
      // exit
      return false;
    }
    // select Token correspond to sessionid
    $select = array('Token');
    // table Authentication
    $from = array(Config::get('ICONNECTION', 'MYSQL', 'T_AUT'));
    // condition
    $where = array(
      array('=', Config::get('ICONNECTION', 'MYSQL', 'T_AUT').'.Session'=>$sessid)
    );
    // query
    $record = $this->database
      ->select($select)
      ->from($from) 
      ->where($where)
      ->query();
    // record exists
    if (empty($record)) {
      // unsuccess
      return false;
    }
    // create token
    $token = $this->generator->create();
    // generated token match with stored token?
    if (strcmp($token, $record[0]->Token) !== 0) {
      // unsuccess
      return false;
    }
    // check time expiration
    if (!$this->datum->difference($record[0]->Expires)) {
      // unsuccess
      return false;
    }
    // select all
    $select = array('*');
    // table Users
    $from = array(Config::get('ICONNECTION', 'MYSQL', 'T_USER'));
    // condition
    $where = array(
      array('=', Config::get('ICONNECTION', 'MYSQL', 'T_USER').'.Id'=>$record[0]->Usersid)
    );
    // query
    $query = $this->database
      ->select($select)
      ->from($from) 
      ->where($where)
      ->query();
    // user
    $user = $query[0];
    // check if user log on
    if (empty($user)) {
      // unsuccess
      return false;
    }
    echo $uri;
    // redirect to last visited uri
    //$this->route->redirect($uri);
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
         ->text(self::USERNAME, 'Meno/Name')
         ->html5Attrs('required')
         ->create();
    // input password field
    $form->input()
         ->password(self::PASSNAME, 'Heslo/Pasword')
         ->html5Attrs('required')
         ->create();
    // submit
    $form->input()
         ->submit('Prihlasenie', '', 'Prihlásenie')
         ->create();
    // check if created columns exist in database
    if ($form->succeedSend($this->database, Config::get('ICONNECTION', 'MYSQL', 'T_USER'))) {
        // process form
        $this->prihlasenieProcess($form);
    }
    // return code
    return $form;
  }

  /***
   * Process login
   * 
   * @param \Vendor\Vendor\Form
   * @return Void
   */
  public function prihlasenieProcess($form)
  {
    // get data
    $data = $form->getData();
    // table
    $table = Config::get('ICONNECTION', 'MYSQL', 'T_USER');

    // username
    $valid[self::USERNAME] = "'".$data[self::USERNAME]."'";
    // passwordname
    $valid[self::PASSNAME] = "'".$this->authenticator->hashpassword($data[self::PASSNAME])."'";
    // validation    
    $valid[self::VALIDATE] = "'Valid'";
    // Authenticate user
    // @var Array, Bool, table
    $user = $this->authenticator->loginUser($valid, $table);
    // user invalid?
    if (empty($user)) {
      // flash message
      Session::set("flash", "Nesprávne meno, heslo alebo neaktívnosť účtu !!!", false);
      // redirect
      Route::redirect("");
    }
    // insert data
    $this->database
      ->insert(array(
	      'Datum'      => date("Y-m-d H:i:s"),
        'Id_Users'   => $user->Id,               
	      'Ip_address' => $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'], 
	      'User_agent' => $_SERVER['HTTP_USER_AGENT'] 
	    ), 
	    Config::get('ICONNECTION', 'MYSQL', 'T_LOG'), 
      true
    );
    // redirect
    Route::redirect($user->Privileges . "/articles/default/");
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
         ->text(self::USERNAME, 'Meno/Name')
         ->html5Attrs('required')
         ->create();
    // input password field
    $form->input()
         ->password(self::PASSNAME, 'Heslo/Pasword')
         ->html5Attrs('required')
         ->create();
    // submit
    $form->input()
         ->submit('Registracia', '', 'Registrácia')
         ->create();

    // check if created columns exist in database
    if ($form->succeedSend($this->database, Config::get('ICONNECTION', 'MYSQL', 'T_USER'))) {
      // callback logon
      $this->registrationProcess($form);
    }
    // return code
    return $form;
  }

  /***
   * Registration process
   * 
   * @param  \Vendor\Vendor\Form
   * @Return Void
   */
  public function registrationProcess($form)
  {
    // get data
    $data = $form->getData();
    // set table
    $table = Config::get('ICONNECTION', 'MYSQL', 'T_USER');
    // if not filled all data
    if (empty($data[self::EMAIL])   || 
        empty($data[self::USERNAME])|| 
        empty($data[self::PASSNAME]))	
    {
      // flash message
      Session::set("flash", "Nevyplnené povinné polia !!!", false);
      // redirect
      Route::redirect("");
    }
    // array for checking user exists
    $array_user_exists = array(
      self::EMAIL    => $data[self::EMAIL], 
      self::USERNAME => $data[self::USERNAME]
    );
    // check if user exists
    if ($this->authenticator->userExists($array_user_exists, $table) === false) {
      // flash sprava
      Session::set("flash", "Užívateľ so zadaným emailom už existuje !!!", false);
      // presmerovanie
      Route::redirect("/front/form/registracia");
    }
    // @Parameters: Sting, String, String
    //   1.@parameter = To
    //   2.@parameter = Name
    //   3.@parameter = Password
    //
    // @return: Array
    //   0.@return => To
    //   1.@return => Subject
    //   2.@return => Message
    //   3.@return => From
    //   4.@return => Validate key
    $parameters = $this->notificator->process(
      $data[self::EMAIL], 
      $data[self::USERNAME], 
      $data[self::PASSNAME]
    );
    // hash password
    $data[self::PASSNAME] = $this->authenticator->hashpassword($data[self::PASSNAME]);
    // remove last item of data
    array_pop($data);
    // add validation key
    $data["Codevalidation"] = $parameters[4];
    // insert into table
    $this->database->insert($data, $table);
    // remove last element
    array_pop($parameters);
    // Odoslanie emailu podla predspracovanych udajov
    $this->notificator->email(\Vendor\Mailer\Mailer::MAIL, $parameters);
  }

  /***
   * Activation process
   * 
   * @param  Void
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

