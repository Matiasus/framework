<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:          Mato Hrinko
* Datum:          05.03.2018 / update
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
    \Vendor\Route\Route as Route;

/** @class formproccess */
class Model {
 
  /** @const  */
  const EMAIL       = 'Email';
  /** @const  */
  const USERNAME    = 'Username';
  /** @const  */
  const PASSNAME    = 'Passwordname';
  /** @const  */
  const VALIDATE    = 'Validation';  
  /** @const  */
  const TYPE_LOGIN  = 'login';
    
  /** @var Object \Vendor\Date\Date */
  private $date;    
  
  /** @var Object \Vendor\Database\Database */
  private $database;

  /** @var Object \Vendor\Generator\Generator */
  private $generator;
  
  /** @var Object \Vendor\Notification\Notification */
  private $notificator; 

  /** @var Object \Vendor\Authenticate\Authenticate */
  private $authenticator;    
  
  /***
   * @desc    Constructor
   *
   * @param   \Vendor\Date\Date   
   * @param   \Vendor\Database\Database
   * @param   \Vendor\Generator\Generator
   * @param   \Vendor\Notification\Notification
   * @param   \Vendor\Authenticate\Authenticate
   *
   * @return  Void
   */
  public function __construct(
    \Vendor\Date\Date $date,
    \Vendor\Database\Database $database,
    \Vendor\Generator\Generator $generator,
    \Vendor\Notification\Notification $notificator,
    \Vendor\Authenticate\Authenticate $authenticator)
  {
    // @var \Vendor\Date\Date
    $this->date = $date;
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
   * @desc    Session login
   * 
   * @param   Void
   *
   * @return  Void
   */
  public function sessionLogin()
  {
    // token session id
    $sessid = Cookie::get(Config::get('COOKIES', 'SESID'));
    // chcek existence COOKIES
    if (empty($sessid)) {
      // exit
      return false;
    }
    //-----------------------------------------------
    // select Token correspond to sessionid
    $select = array('*');
    // table Authentication
    $from = array(Config::get('ICONNECTION', 'MYSQL', 'T_AUT'));
    // condition
    $where = array(
      array('=', Config::get('ICONNECTION', 'MYSQL', 'T_AUT').'.Sessionid'=>$sessid)
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
    if (!$this->date->difference($record[0]->Expire)) {
      // unsuccess
      return false;
    }
    // select all
    $select = array('*');
    // table Users
    $from = array(Config::get('ICONNECTION', 'MYSQL', 'T_USER'));
    // condition
    $where = array(
      array('=', Config::get('ICONNECTION', 'MYSQL', 'T_USER').'.Id'=>$record[0]->Id_Users)
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
    //-----------------------------------------------
    // select last uri correspond to sessionid
    $select = array('*');
    // table visits
    $from = array(Config::get('ICONNECTION', 'MYSQL', 'T_VISIT'));
    // condition
    $where = array(
      array('=', Config::get('ICONNECTION', 'MYSQL', 'T_VISIT').'.Sessionid_Logins'=>$sessid)
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
    // last uri
    $uri = $record[count($record)-1]->Page;
    //-----------------------------------------------
    // empty url - return / dengerous infinitive loop /
    if (empty($uri) || !isset($uri) || $uri === "/") {
      // unsuccess
      return false;
    }
    // redirect to last visited uri
    Route::redirect($uri);
  }


  /***
   * @desc   Form for logon
   * 
   * @param  \Vendor\Form\Form
   *
   * @return String
   */
  public function showFormPrihlasenie(\Vendor\Form\Form $form)
  {
    // create form
    $form
      ->attrs(array(
         'action'=>Route::getfullUri(true)
        ,'method'=>'post'))
      ->content(array(
        'input'=>array(array(
           'type'=>'text'
          ,'name'=>self::USERNAME
          ,'label'=>'Meno/Name'
          ,'placeholder'=>'Meno/Name'
          ,'id'=>'id-'.strtolower(self::USERNAME)
          ,'required'=>'true')),
        'input-password'=>array(array(
          'type'=>'password'
          ,'name'=>self::PASSNAME 
          ,'label'=>'Heslo/Pasword' 
          ,'placeholder'=>'Heslo/Pasword'
          ,'id'=>'id-'.strtolower(self::PASSNAME) 
          ,'required'=>'true')),
        'input-submit'=>array(array(
          'type'=>'submit' 
          ,'name'=>'Prihlasenie'
          ,'value'=>'Prihlásenie' 
          ,'id'=>'id-submit'))
      )
    );
    // check if created columns exist in database
    if ($form->succeedSend($this->database, Config::get('ICONNECTION', 'MYSQL', 'T_USER'))) {
        // process form
        $this->prihlasenieProcess($form);
    }
    // return code
    return $form;
  }

  /***
   * @desc    Process login
   * 
   * @param   \Vendor\Vendor\Form
   *
   * @return  Void
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
    $this->database->insert(array(
      'Login'      => date("Y-m-d H:i:s"),
      'Id_Users'   => $user->Id,
      'Sessionid'  => session_id(),
      'Ip_address' => $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'], 
      'User_agent' => $_SERVER['HTTP_USER_AGENT']
      ), 
      Config::get('ICONNECTION', 'MYSQL', 'T_LOG'), 
      true
    );
    // redirect
    Route::redirect($user->Privileges . "/home/default/");
  }

  /***
   * @desc    Form for registration
   * 
   * @param   \Vendor\Form\Form
   *
   * @return  String
   */
  public function showFormRegistracia(\Vendor\Form\Form $form)
  {
    // create form
    $form
      ->attrs(array(
         'action'=>Route::getfullUri(true)
        ,'method'=>'post'))
      ->content(array(
        'input-email'=>array(array(
           'type'=>'text'
          ,'name'=>'Email'
          ,'label'=>'E-mail'
          ,'placeholder'=>'Email'
          ,'id'=>'id-email'
          ,'required'=>'true')),
        'input-username'=>array(array(
           'type'=>'text'
          ,'name'=>self::USERNAME
          ,'label'=>'Meno/Name'
          ,'placeholder'=>'Meno/Name'
          ,'id'=>'id-'.strtolower(self::USERNAME)
          ,'required'=>'true')),
        'input-password'=>array(array(
          'type'=>'password'
          ,'name'=>self::PASSNAME 
          ,'label'=>'Heslo/Pasword' 
          ,'placeholder'=>'Heslo/Pasword'
          ,'id'=>'id-'.strtolower(self::PASSNAME) 
          ,'required'=>'true')),
        'input-submit'=>array(array(
          'type'=>'submit' 
          ,'name'=>'Prihlasenie'
          ,'value'=>'Prihlásenie' 
          ,'id'=>'id-submit'))
      )
    );
    // check if created columns exist in database
    if ($form->succeedSend($this->database, Config::get('ICONNECTION', 'MYSQL', 'T_USER'))) {
      // callback logon
      $this->registrationProcess($form);
    }
    // return code
    return $form;
  }

  /***
   * @desc    Registration process
   * 
   * @param   \Vendor\Vendor\Form
   *
   * @Return  Void
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
   * @desc    Activation process
   * 
   * @param   Void
   *
   * @return  Void
   */
  public function activation()
  {
    // get code validation
    $code = Route::get('params1');
    // table Users
    $table_users = Config::get('ICONNECTION', 'MYSQL', 'T_USER');
    // check if code validation accepted
    if (!empty($code)) {
      // select
      $select = array('Id');
      // from 
      $from = array($table_users);
      // condition
      $where = array(
        array(
          '=', 
          $table_users.'.Codevalidation' => $code
        )
      );
      // query
      $user = $this->database
        ->select($select)
        ->from($from) 
        ->where($where)
        ->query();        
      // if user with codevalidation exists 
      if (count($user) == 1) {
        // update status: invalid => valid
        $this->database->update(
          array("Validation" => "valid"), 
          array("Codevalidation" => $code), 
          $table_users
        );
        // flash message announcement
        Session::set("flash", "Váš účet bol úspešne aktivovaný, pokračujte prosím prihlásením!", false);
        // redirect to home page
        Route::redirect("");
      }
    }
  }
}
