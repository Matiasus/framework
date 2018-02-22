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
	private $tab_users;

	/** @var String - tabulka Poznamok */
	private $tab_articles;
  
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
    $this->tab_users = Config::get('ICONNECTION', 'MYSQL', 'T_USER');
    // table articles
    $this->tab_articles = Config::get('ICONNECTION', 'MYSQL', 'T_ART');
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
      $this->tab_articles.'.Id as id',
			$this->tab_articles.'.Title as title',
			$this->tab_articles.'.Title_unaccent as title_unaccent',
			$this->tab_articles.'.Category as category',
      $this->tab_articles.'.Category_unaccent as category_unaccent',
      $this->tab_articles.'.Type as type',
      'DATE_FORMAT('.$this->tab_articles.'.Registered, \'%d.%b. %Y\') as registered',
			$this->tab_users.'.Username',
      'LOWER('.$this->tab_users.'.Username) as username');
    // from
    $from = array($this->tab_articles, 
      array($this->tab_users,
        $this->tab_articles.'.Id_Users'=>$this->tab_users.'.Id'
    ));
    // where
    $where = array(
      array('=',$this->tab_articles.'.Id_Users'=>$user['Id']
    ));
    // zotriedenie
    $order = array($this->tab_articles.'.Category', $this->tab_articles.'.Title');
    // spracovanie poziadavky
    $record = $this->database
      ->select($select)
      ->from($from) 
      ->where()
      ->order($order)
      ->query();

    // articles
    $variables = array('articles'=>$record, 'privileges'=>$user['Privileges']);
    // return variables
    return $variables;
  }
 }

