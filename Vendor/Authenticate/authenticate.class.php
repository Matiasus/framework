<?php 

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:        Mato Hrinko
* Datum:        07.12.2016 / update
* Adresa:       http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiracia: 		
*
***/
namespace Vendor\Authenticate;

// use
use \Vendor\Session\Session as Session,
    \Vendor\Cookie\Cookie as Cookie,
    \Vendor\Config\File as Config,
    \Vendor\Date\Date as Date;

/** @class Authenticate */
class Authenticate {

  /** @const */
  const CONNECTION = " AND ";
  
  /** @var String */
  private $tb_session;
  
  /** @var Objekt \Vendor\User\User */
  private $user;
  
  /** @var Objekt \Vendor\Database\Database */
  private $database;
  
  /** @var Objekt \Vendor\Generator\Generator */
  private $generator;  
  
  /***
   * @desc    Constructor
   *
   * @param   \Vendor\User\User
   * @param   \Vendor\Database\Database
   * @param   \Vendor\Generator\Generator
   *
   * @return  Void
   */
  public function __construct(
    \Vendor\User\User $user,
    \Vendor\Database\Database $database,
    \Vendor\Generator\Generator $generator)
  {
    // @var Object \Vendor\Database\Database
    $this->user = $user;    
    // @var Object \Vendor\Database\Database
    $this->database = $database;
    // @var Object \Vendor\Generator\Generator
    $this->generator = $generator;
    // authentication table
    $this->tb_session = Config::get('ICONNECTION', 'MYSQL', 'T_AUT');    
  }

  /**
   * Check login
   *
   * @param Array
   * @return False | Array
   */
  public function loginUser($data = array(), $table)
  {
    // condition
    $condition = '';
    // check if user not empty
    if (!empty($data)) {
      // loop
      foreach ($data as $key => $value) {
        // build condition
        $condition .= $table.'.'.$key.'='.$value.' AND ';	
      }
      // substring last ' AND '
      $condition = substr($condition, 0, strlen($condition) - 5);
      // query
      $query = "SELECT Id, Username, Email, Privileges, Registration FROM ".$table." WHERE ".$condition.";";
      // user
      $user = $this->database->query($query);
      // user exists? 
      if (count($user) === 1) {
        // logoff logged user
        $this->user->remove();
        // store user
        $this->user->store($user[0]);
        // store session of user
        $this->storeLoginSession($user[0]);
        // return user      
        return $user[0];
      }
    }
    // unsuccess
    return false;
  }

  /**
   * Check if user exists
   *
   * @param  Array
   * @return Boolean
   */
  public function userExists($data = array(), $table)
  {
    // condition
    $condition = '';
    // check if user not empty
    if (!empty($data)) {
      // loop
      foreach ($data as $key => $value) {
        // build condition
        $condition .= $table.'.'.$key.'=\''.$value.'\' AND ';	
      }
      // substring last ' AND '
      $condition = substr($condition, 0, strlen($condition) - 5);
      // compose query
      $query = "SELECT Id FROM ".$table." WHERE ".$condition.";";
      // query table
      $user = $this->database->query($query);
      // user exists? 
      if (count($user) > 0) {
        // user exists
        return false;
      }
    }
    // user not exists
    return true;
  }

  /**
   * Persistent login
   *
   * @param Array
   * @return False | Array
   */
  public function storeLoginSession($user)
  {
    // create token
    $token = $this->generator->create();
    // Get actual time
    $actual = Date::getActualTime();
    // expire date
    $expire = Date::getFutureTime(Config::getArray('DATE')['EXPIR']);
    
    // query
    $query = "SELECT Current FROM ".$this->tb_session." WHERE Id_Users = '".$user->Id."';";
    // user
    $last = $this->database->query($query);
    
    // user not exists in table
    if (empty($last)) {
      // insert data
      $insert = array(
        "Token"     => $token,
        "Id_Users"  => $user->Id,
        "Sessionid" => session_id(),
        "Current"   => $actual,
        "Expire"    => $expire
      );
      // insert data
      $this->database->insert($insert, $this->tb_session);
      // if exists
    } else {
      // update values
      $update = array(
        "Token"     => $token,
        "Sessionid" => session_id(),
        "Current"   => $actual,	    
        "Expire"    => $expire,
        "Last"      => $last[0]->Current
      );
      // Update udajov do databazy
      $this->database->update(
        $update, 
        array("Id_Users" => $user->Id),
        $this->tb_session
      ); 
    }
  }
  
  /**
   * Hash
   *
   * @param  String 
   * @return String 
   */
  public function hashpassword($password)
  {
    // HASH pasword
    return hash("sha256", $password);
  }    
}
