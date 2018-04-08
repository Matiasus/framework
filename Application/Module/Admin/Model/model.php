<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:          Mato Hrinko
* Datum:          04.03.2018 / update
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

/** @class Model */
class Model {

  /** @var Object \Vendor\User\User */
  private $user;

  /** @var Object \Vendor\Database\Database */
  private $database;

  /** @var String User table */
  private $tab_users;

  /** @var String Articles table */
  private $tab_articles;

  /** @var String Articles table */
  private $tab_components;
  
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
    // table components
    $this->tab_components = Config::get('ICONNECTION', 'MYSQL', 'T_COMP');
  }

  /***
  * @desc   Show all articles
  * 
  * @param  \Vendor\Html\Html
  * @return Void
  */
  public function showAllComponents(\Vendor\Html\Html $html)
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // articles
    $select = array(
      $this->tab_components.'.Id',
      $this->tab_components.'.Category',
      $this->tab_components.'.Category_unaccent',
      $this->tab_components.'.Description',
      $this->tab_components.'.Description_unaccent',
      $this->tab_components.'.Amount',
      'DATE_FORMAT('.$this->tab_components.'.Registered, \'%d.%b. %Y\') as Registered'
    );
    // from
    $from = array(
      $this->tab_components, 
      array()
    );
    // condition
    $where = array(
    );
    // ordering
    $order = array(
      $this->tab_components.'.Category', 
      $this->tab_components.'.Description'
    );
    // process query
    $record = $this->database
      ->select($select)
      ->from($from) 
      ->where($where)
      ->order($order)
      ->query();
    // articles
    $variables = array(
      'components'=>$record, 
      'privileges'=>$user['Privileges']
    );

    // return variables
    return $variables;
  }

  /***
  * @desc   Show all articles
  * 
  * @param  Void
  * @return Void
  */
  public function showAllArticles()
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
      'LOWER('.$this->tab_users.'.Username) as username'
    );
    // from
    $from = array(
      $this->tab_articles, 
      array(
        $this->tab_users,
        $this->tab_articles.'.Id_Users'=>$this->tab_users.'.Id'
      )
    );
    // condition
    $where = array(
      array(
        '=',
        $this->tab_articles.'.Id_Users'=>$user['Id']
      )
    );
    // ordering
    $order = array(
      $this->tab_articles.'.Category', 
      $this->tab_articles.'.Title'
    );
    // process query
    $record = $this->database
      ->select($select)
      ->from($from) 
      ->where($where)
      ->order($order)
      ->query();
    // articles
    $variables = array(
      'articles'=>$record, 
      'privileges'=>$user['Privileges']
    );

    // return variables
    return $variables;
  }

  /***
  * @desc   Show category articles
  * 
  * @param  Void
  * @return Void
  */
  public function showCategoryArticles()
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
      'LOWER('.$this->tab_users.'.Username) as username'
    );
    // from
    $from = array(
      $this->tab_articles, 
      array(
        $this->tab_users,
        $this->tab_articles.'.Id_Users'=>$this->tab_users.'.Id'
      )
    );
    // condition
    $where = array(
      array(
        '=',  
        $this->tab_articles.'.Category_unaccent'=>Route::get('controller')
      )
    );
    // ordering
    $order = array(
      $this->tab_articles.'.Category', 
      $this->tab_articles.'.Title'
    );
    // process query
    $record = $this->database
      ->select($select)
      ->from($from) 
      ->where($where)
      ->order($order)
      ->query();

    // articles
    $variables = array(
      'articles'=>$record, 
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }

  /***
  * @desc   Datails articles
  * 
  * @param  Void
  * @return Void
  */
  public function showDetailArticle()
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
      $this->tab_articles.'.Content as content',
      'DATE_FORMAT('.$this->tab_articles.'.Registered, \'%d.%b. %Y\') as registered',
      $this->tab_users.'.Username',
      'LOWER('.$this->tab_users.'.Username) as username'
    );
    // odkial
    $from = array(
      $this->tab_articles, 
      array(
        $this->tab_users,
        $this->tab_articles.'.Id_Users'=>$this->tab_users.'.Id'
      )
    );
    // podmienka
    $where = array(
      array(
        '=',
        $this->tab_articles.'.Id'=>Route::get('params2')
      )
    );
    // zotriedenie
    $order = array(
      $this->tab_articles.'.Category', 
      $this->tab_articles.'.Title'
    );
    // spracovanie poziadavky
    $record = $this->database
      ->select($select)
      ->from($from) 
      ->where($where)
      ->order($order)
      ->query();

    // articles
    $variables = array(
      'article'=>$record[0], 
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }
}

