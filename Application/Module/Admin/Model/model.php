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
namespace Application\Module\Admin\Model;

// use
use \Vendor\Session\Session as Session,
    \Vendor\Cookie\Cookie as Cookie,
    \Vendor\Buffer\Buffer as Buffer,
    \Vendor\Config\File as Config,
    \Vendor\Route\Route as Route,
    \Vendor\Date\Date as Date;

/** @class formproccess */
class Model {

  /** @var Object \Vendor\User\User */
  private $user;
 
  /** @var Object \Vendor\Database\Database */
  private $database;

  /** @var String - tabulka Uzivatelov */
  private $table_users;

  /** @var String - tabulka Poznamok */
  private $table_articles;
  
  /***
   * Constructor
   *
   * @param  \Vendor\User\User
   * @return Void
   */
  public function __construct(\Vendor\User\User $user, \Vendor\Database\Database $database)
  {
    // @var \Vendor\User\User
    $this->user = $user;
    // @var \Vendor\Database\Database
    $this->database = $database;
    // table articles
    $this->table_users = Config::get('ICONNECTION', 'MYSQL', 'T_USER');
    // table articles
    $this->table_articles = Config::get('ICONNECTION', 'MYSQL', 'T_ART');
  }

  /***
  * 
  * 
  * @param  Void
  * @return Void
  */
  public function showArticles()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // articles
    $select = array(
      $this->table_articles.'.Id as id',
      $this->table_articles.'.Title as title',
      $this->table_articles.'.Title_unaccent as title_unaccent',
      $this->table_articles.'.Category as category',
      $this->table_articles.'.Category_unaccent as category_unaccent',
      $this->table_articles.'.Type as type',
      'DATE_FORMAT('.$this->table_articles.'.Registered, \'%d.%b. %Y\') as registered', $this->table_users.'.Username',
      'LOWER('.$this->table_users.'.Username) as username');
    // from
    $from = array(
      // table name
      $this->table_articles, 
      // array if join clausula used 
      array(
        // join table
        $this->table_users,
        // condition for from join selection
        $this->table_articles.'.Id_Users'=>$this->table_users.'.Id'
    ));
    // where
    $where = array(
      array('=',$this->table_articles.'.Id_Users'=>$user['Id']
    ));
    // ordering
    $order = array($this->table_articles.'.Category', $this->table_articles.'.Title');
    // result from query
    $records = $this->database
      ->select($select)
      ->from($from) 
      ->where($where)
      ->order($order)
      ->query();
    // articles
    $variables = array('articles'=>$records, 'privileges'=>$user['Privileges']);
    // return variables
    return $variables;
  }
 }

