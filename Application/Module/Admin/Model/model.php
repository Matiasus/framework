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

  /** @var String Run table */
  private $tab_run;

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
    $this->tab_run = Config::get('ICONNECTION', 'MYSQL', 'T_RUN');
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
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/articles/default',
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
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/articles/default',
      'subdir' => $user['Privileges'].'/'.$record[0]->category_unaccent.'/default/',
      'category' => $record[0]->category,
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
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/articles/default',
      'subdir' => $user['Privileges'].'/'.Route::get('controller').'/default',
      'article'=>$record[0], 
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }

  /***
   * @desc   Show sport run
   * 
   * @param  Void
   * @return Void
   */
  public function showSportRun()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // records 
    $records = $this->database->query("
      SELECT 
        $this->tab_run.Id as id,
        $this->tab_run.Category as category,
        $this->tab_run.Category_unaccent as category_unaccent,
        $this->tab_run.Length as length,
        $this->tab_run.Elapsed as elapsed,
        DATE_FORMAT($this->tab_run.Registered, '%d.%m.%Y') as registered,
        LOWER($this->tab_users.Username) as username
      FROM $this->tab_run
      INNER JOIN $this->tab_users
        ON $this->tab_run.Id_Users = $this->tab_users.Id
      WHERE
        $this->tab_run.Id_Users=".intval($user['Id'])." AND
        YEAR($this->tab_run.Registered) = 2019
      ORDER BY
        $this->tab_run.Registered DESC
    ");

    // check if records were found
    if (!empty($records)) {
      // calculate time per 1 km
      foreach ($records as $record) {
        // convert to variable
        list($hours, $minutes, $seconds) = sscanf($record->elapsed, "%d:%d:%d");
        // load to array
        $record->time_per_km = gmdate("H:i:s", ($hours*3600 + $minutes*60 + $seconds)/($record->length/1000));
      }
      // variables
      $variables = array(
        'category' => $records[0]->category,
        'runs'=>$records,
        'root' => $user['Privileges'].'/home/default',
        'dir' => $user['Privileges'].'/sports/default',
        'privileges'=>$user['Privileges']
      );
    } else {
      // variables
      $variables = array(
        'root' => $user['Privileges'].'/home/default',
        'dir' => $user['Privileges'].'/sports/default',
        'privileges'=>$user['Privileges']
      );
    }
    // return variables
    return $variables;
  }
}

