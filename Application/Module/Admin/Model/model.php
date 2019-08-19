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

  /** @const  */
  const TITLE    = 'Title';
  /** @const  */
  const CATEGORY = 'Category';
  /** @const  */
  const CONTENT  = 'Content';

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
   * @param  \Vendor\Database\Database
   *
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
   *
   * @return Array
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
  *
  * @return Array
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
  *
  * @return Array
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
   *
   * @return Array
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
        $this->tab_run.Id,
        $this->tab_run.Category,
        $this->tab_run.Category_unaccent,
        $this->tab_run.Length,
        $this->tab_run.Elapsed,
        $this->tab_run.Pulse_avg,
        $this->tab_run.Fitness_level,
        $this->tab_run.Fitness_pulse,
        $this->tab_run.Calories,
        $this->tab_run.Fat,
        DATE_FORMAT($this->tab_run.Registered, '%d.%m.%Y') as Registered,
        LOWER($this->tab_users.Username)
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
        list($hours, $minutes, $seconds) = sscanf($record->Elapsed, "%d:%d:%d");
        // load to array
        $record->Time_per_km = gmdate("H:i:s", ($hours*3600 + $minutes*60 + $seconds)/($record->Length/1000));
      }
      // variables
      $variables = array(
        'category' => $records[0]->Category,
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

  /***
   * @desc   Show sport run detail
   *
   * @param  Void
   *
   * @return Array
   */
  public function showSportRunDetail()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // records
    $records = $this->database->query("
      SELECT
        $this->tab_run.Id,
        $this->tab_run.Category,
        $this->tab_run.Category_unaccent,
        $this->tab_run.Length,
        $this->tab_run.Elapsed,
        $this->tab_run.Pulse_avg,
        $this->tab_run.Fitness_level,
        $this->tab_run.Fitness_pulse,
        $this->tab_run.Calories,
        $this->tab_run.Fat,
        DATE_FORMAT($this->tab_run.Registered, '%d.%m.%Y') as Registered,
        LOWER($this->tab_users.Username)
      FROM $this->tab_run
      INNER JOIN $this->tab_users
        ON $this->tab_run.Id_Users = $this->tab_users.Id
      WHERE
        $this->tab_run.Id_Users=".intval($user['Id'])." AND
        YEAR($this->tab_run.Registered) = 2019 AND
        $this->tab_run.Length=".ucfirst(Route::get('params2'))."
      ORDER BY
        $this->tab_run.Registered DESC
    ");

    // check if records were found
    if (!empty($records)) {
      // calculate time per 1 km
      foreach ($records as $record) {
        // convert to variable
        list($hours, $minutes, $seconds) = sscanf($record->Elapsed, "%d:%d:%d");
        // load to array
        $record->Time_per_km = gmdate("H:i:s", ($hours*3600 + $minutes*60 + $seconds)/($record->Length/1000));
      }
      // variables
      $variables = array(
        'category' => $records[0]->Category,
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

  /***
   * @desc   Add article
   *
   * @param  Void
   *
   * @return Array
   */
  public function addArticle()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // variables
    $variables = array(
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/articles/default',
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }

  /***
   * @desc   Form for logon
   *
   * @param  \Vendor\Form\Form
   *
   * @return String
   */
  public function showFormAdd(\Vendor\Form\Form $form)
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // set method
    $form->setMethod(Config::get('FORM', 'METHOD_POST'));
    // set action
    $form->setAction(Route::getfullUri(true));
    // set form
    $form->setInline(false);
    // input text field
    $form->input('text')
         ->attr(array('name'=>'Category', 'label'=>'Kategória', 'id'=>'id-category'))
         ->html5Attrs('required')
         ->create();
    // input password field
    $form->input('text')
         ->attr(array('name'=>'Title', 'label'=>'Titul', 'id'=>'id-title'))
         ->html5Attrs('required')
         ->create();
    // input content textarea
    $form->textarea()
         ->attr(array('name'=>'Content', 'label'=>'Obsah','id'=>'editor'))
         ->create();
    // submit
    $form->input('submit')
         ->attr(array('name'=>'Submit', 'value'=>'Odošli', 'id'=>'id-submit', 'onclick'=>'getData("editor");'))
         ->create();
    // check if created columns exist in database
    if ($form->succeedSend($this->database, $this->tab_articles)) {
        // process form
        $this->addProcess($form, $user);
    }
    // return code
    return $form;
  }

  /***
   * @desc    Process login
   *
   * @param   \Vendor\Form\Form
   * @param   \Vendor\User\User
   *
   * @return  Void
   */
  public function addProcess($form, $user)
  {
    // get data
    $data = $form->getData();
    // table
    $table = $this->tab_articles;
    // insert data
    $this->database->insert(array(
      'Id_Users' => $user['Id'],
      'Category' => $data[self::CATEGORY],
      'Category_unaccent' => $this->database->unAccentUrl($data[self::CATEGORY]),
      'Title' => $data[self::TITLE],
      'Title_unaccent' => $this->database->unAccentUrl($data[self::TITLE]),
      'Content' => $data[self::CONTENT],
      'Type' => 'draft'
      ),
      $this->tab_articles,
      true
    );
    // redirect
    Route::redirect($user['Privileges'] . "/articles/default/");
  }
  
  /***
   * @desc   Edit article
   *
   * @param  Void
   *
   * @return Array
   */
  public function editArticle()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // variables
    $variables = array(
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/articles/default',
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }
  
  /***
   * @desc   Form for edit
   *
   * @param  \Vendor\Form\Form
   *
   * @return String
   */
  public function showFormEdit(\Vendor\Form\Form $form)
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    
    $article = Route::get('params2');
    
    // records
    $record = $this->database->query("
      SELECT
        $this->tab_articles.Id,
        $this->tab_articles.Category,
        $this->tab_articles.Title,
        $this->tab_articles.Content
      FROM $this->tab_articles
      WHERE
        $this->tab_articles.Id=".intval($article)."
    "); 
    
    if (!empty($record)) {
      if (is_array($record)) {
        $record = $record[0];
      }
    }
    
    // set method
    $form->setMethod(Config::get('FORM', 'METHOD_POST'));
    // set action
    $form->setAction(Route::getfullUri(true));
    // set form
    $form->setInline(false);
    // input text field
    $form->input('text')
         ->attr(array('name'=>'Category', 'label'=>'Kategória', 'value'=>$record->Category, 'id'=>'id-category'))
         ->html5Attrs('required')
         ->create();
    // input password field
    $form->input('text')
         ->attr(array('name'=>'Title', 'label'=>'Titul', 'value'=>$record->Title, 'id'=>'id-title'))
         ->html5Attrs('required')
         ->create();
    // input content textarea
    $form->textarea()
         ->attr(array('name'=>'Content', 'label'=>'Obsah', 'id'=>'editor'))
         ->create();
    // submit
    $form->input('submit')
         ->attr(array('name'=>'Submit', 'value'=>'Odošli', 'id'=>'id-submit', 'onclick'=>'getData("editor");'))
         ->create();
    // check if created columns exist in database
    if ($form->succeedSend($this->database, $this->tab_articles)) {
        // process form
        $this->editProcess($form, $user);
    }
    // return code
    return $form;
  }

  /***
   * @desc    Process edit
   *
   * @param   \Vendor\Form\Form
   * @param   \Vendor\User\User
   *
   * @return  Void
   */
  public function editProcess($form, $user)
  {
    // get data
    $data = $form->getData();
    // table
    $table = $this->tab_articles;
    // insert data
    $this->database->insert(array(
      'Id_Users' => $user['Id'],
      'Category' => $data[self::CATEGORY],
      'Category_unaccent' => $this->database->unAccentUrl($data[self::CATEGORY]),
      'Title' => $data[self::TITLE],
      'Title_unaccent' => $this->database->unAccentUrl($data[self::TITLE]),
      'Content' => $data[self::CONTENT],
      'Type' => 'draft'
      ),
      $this->tab_articles,
      true
    );
    // redirect
    Route::redirect($user['Privileges'] . "/articles/default/");
  }  

  /***
   * @desc   Add article
   *
   * @param  Void
   *
   * @return Array
   */
  public function addtimeSportsRun()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // variables
    $variables = array(
      'category' => 'Beh',
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/sports/default',
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }

  /***
   * @desc   Form for add time
   *
   * @param  \Vendor\Form\Form
   *
   * @return String
   */
  public function showFormAddtime(\Vendor\Form\Form $form)
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // set method
    $form->setMethod(Config::get('FORM', 'METHOD_POST'));
    // set action
    $form->setAction(Route::getfullUri(true));
    // set form
    $form->setInline(true);
    // input text field
    $form->input('text')
      ->attr(array('name'=>'Category', 'label'=>'Kategória', 'value'=>'Beh', 'id'=>'id-category'))
      ->html5Attrs('required')
      ->create();
    // input number field
    $form->input('number')
      ->attr(array('name'=>'Length', 'label'=>'Vzdialenosť', 'value'=>2500, 'step'=>250, 'id'=>'id-length'))
      ->html5Attrs('required')
      ->create();
    // input time field
    $form->input('time')
      ->attr(array('name'=>'Elapsed', 'label'=>'Čas', 'id'=>'id-elapsed', 'value'=>'00:10:00', 'step'=>'1'))
      ->html5Attrs('required')
      ->create();
    // input time field
    $form->input('number')
      ->attr(array('name'=>'Pulse_avg', 'label'=>'Pulz', 'id'=>'id-pulse'))
      ->create();
    // pulse
    $form->input('number')
      ->attr(array('name'=>'Fitness_level', 'label'=>'Fitnes level', 'id'=>'id-level'))
      ->create();
    // fitness level pulse
    $form->input('text')
      ->attr(array('name'=>'Fitness_pulse', 'label'=>'Fitnes pulz', 'id'=>'id-pulse'))
      ->create();
    // pulse
    $form->input('number')
      ->attr(array('name'=>'Calories', 'label'=>'Kalórie', 'id'=>'id-calories'))
      ->create();
    // pulse
    $form->input('number')
      ->attr(array('name'=>'Fat', 'label'=>'Tuk', 'id'=>'id-fat'))
      ->create();
    // submit
    $form->input('submit')
      ->attr(array('name'=>'Submit', 'value'=>'Odošli', 'id'=>'id-submit'))
      ->create();
    // check if created columns exist in database
    if ($form->succeedSend($this->database, $this->tab_run)) {
      // process form
      $this->addTimeProcess($form, $user);
    }
    // return code
    return $form;
  }

  /***
   * @desc    Process login
   *
   * @param   \Vendor\Vendor\Form
   * @param   \Vendor\User\User
   *
   * @return  Void
   */
  public function addtimeProcess($form, $user)
  {
    // get data
    $data = $form->getData();
    // add user id
    $toDB = array(
      'Id_Users' => $user['Id']
    );
    // sanitize empty value
    foreach ($data as $key => $value) {
      // check for non empty value and submit
      if(!empty($value) && strcmp('Submit', $key) !== 0) {
        // add key not empty value
        $toDB[$key] = $value;
        // category unaccent
        if (strcmp('Category', $key) === 0) {
          // add unaccent category
          $toDB[$key.'_unaccent'] = $this->database->unAccentUrl($value);        
        }
      }
    }
    
    print_r($toDB);

    // insert data
    $this->database->insert(
      $toDB,
      $this->tab_run,
      true
    );
    // redirect
    Route::redirect($user['Privileges'] . "/sports/default/");
  }


}

