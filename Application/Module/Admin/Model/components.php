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
class Components {

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
    // components
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
    // variables to view
    $variables = array(
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/components/default',
      'components'=>$record, 
      'privileges'=>$user['Privileges']
    );

    // return variables
    return $variables;
  }
  /***
   * @desc   Show category components
   * 
   * @param  Void
   * @return Void
   */
  public function showCategoryComponents()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // components
    $select = array(
      $this->tab_components.'.Id',
      $this->tab_components.'.Category',
      $this->tab_components.'.Category_unaccent',
      $this->tab_components.'.Description',
      $this->tab_components.'.Description_unaccent',
      $this->tab_components.'.Amount'
    );
    // from
    $from = array(
      $this->tab_components, 
      array()
    );
    // condition
    $where = array(
      array(
        '=',  
        $this->tab_components.'.Category_unaccent'=>Route::get('params1')
      )
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

    // variables to view
    $variables = array(
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/components/default',
      'component' =>Route::get('params1'),
      'components'=>$record, 
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }

  /***
   * @desc   Add variables
   * 
   * @param  Void
   * @return Void
   */
  public function formAddVariables()
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // variables to view
    $variables = array(
      'privileges'=>$user['Privileges']
    );
    // return variables
    return $variables;
  }

  /***
   * @desc   Form for add component
   * 
   * @param  \Vendor\Form\Form
   *
   * @return String
   */
  public function showFormAdd(\Vendor\Form\Form $form)
  {
    // set method
    $form->setMethod(Config::get('FORM', 'METHOD_POST'));
    // set action
    $form->setAction(Route::getfullUri(true));
    // set form
    $form->setInline(true);
    // input text field
    $form->input()
         ->text('Category', 'Komponent')
         ->html5Attrs('required')
         ->create();
    // input text field
    $form->input()
         ->text('Description', 'Popis')
         ->html5Attrs('required')
         ->create();
    // input text field
    $form->input()
         ->text('Amount', 'Počet')
         ->html5Attrs('required')
         ->create();
    // submit
    $form->input()
         ->submit('Submit', '', 'Vlož')
         ->create();
    // check if created columns exist in database
    if ($form->succeedSend($this->database, Config::get('ICONNECTION', 'MYSQL', 'T_COMP'))) {
        // process form
        $this->addProcess($form);
    }
    // return code
    return $form;
  }

  /***
   * @desc    Process add components
   * 
   * @param   \Vendor\Vendor\Form
   *
   * @return  Void
   */
  public function addProcess($form)
  {
    // if user is not logged in
    if (!($user = $this->user->getLoggedUser())) {
      // redirect to login
      Route::redirect("");
    }
    // get data
    $data = $form->getData();
    // table
    $table = Config::get('ICONNECTION', 'MYSQL', 'T_COMP');
    // insert data
    $this->database->insert(array(
      'Category'=> $data['Category'],
      'Category_unaccent' => $this->database->unAccentUrl($data['Category']),
      'Description' => $data['Description'],
      'Description_unaccent' => $this->database->unAccentUrl($data['Description']),
      'Amount' => $data['Amount'], 
      'Registered'=> date("Y-m-d H:i:s")
      ), 
      $table, 
      true
    );
    // redirect
    Route::redirect($user['Privileges']."/home/default/");
  }

}

