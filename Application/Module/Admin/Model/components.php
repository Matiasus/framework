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

  /** @var String Components table */
  private $tab_components;

  /** @var String Receivers table */
  private $tab_receivers;
  
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
    // table receivers
    $this->tab_receivers = Config::get('ICONNECTION', 'MYSQL', 'T_RECE');
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

    // records 
    $records = $this->database->query("
      SELECT 
        $this->tab_receivers.Mark as mark,
        $this->tab_receivers.Mark_unaccent as mark_unaccent,
        $this->tab_receivers.Type as type,
        $this->tab_receivers.Type_unaccent as type_unaccent,
        $this->tab_components.Id as id,
        $this->tab_components.Category as category,
        $this->tab_components.Category_unaccent as category_unaccent,
        $this->tab_components.Description as description,
        $this->tab_components.Description_unaccent as description_unaccent,
        $this->tab_components.Label as label,
        $this->tab_components.Amount as amount
      FROM $this->tab_components
      INNER JOIN $this->tab_receivers
        ON $this->tab_components.Id_Receivers = $this->tab_receivers.Id
      ORDER BY 
        $this->tab_receivers.Mark, $this->tab_receivers.Type, $this->tab_components.Description
    ");

    // check if records were found
    if (empty($records)) {
      // unsuccess
      return false;
    }

    // variables to view
    $variables = array(
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/components/default',
      'components'=>$records, 
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
    // records 
    $records = $this->database->query("
      SELECT 
        $this->tab_receivers.Mark as mark,
        $this->tab_receivers.Mark_unaccent as mark_unaccent,
        $this->tab_receivers.Type as type,
        $this->tab_receivers.Type_unaccent as type_unaccent,
        $this->tab_components.Id as id,
        $this->tab_components.Category as category,
        $this->tab_components.Category_unaccent as category_unaccent,
        $this->tab_components.Description as description,
        $this->tab_components.Description_unaccent as description_unaccent,
        $this->tab_components.Label as label,
        $this->tab_components.Amount as amount
      FROM $this->tab_components
      INNER JOIN $this->tab_receivers
        ON $this->tab_components.Id_Receivers = $this->tab_receivers.Id
      WHERE
        $this->tab_receivers.".ucfirst(Route::get('params1'))."_unaccent='".Route::get('params2')."'
    ");

    // check if records were found
    if (empty($records)) {
      // unsuccess
      return false;
    }

    // variables to view
    $variables = array(
      'root' => $user['Privileges'].'/home/default',
      'dir' => $user['Privileges'].'/components/default',
      'component' =>Route::get('params2'),
      'components'=>$records, 
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
    //Route::redirect($user['Privileges']."/home/default/");
  }

}

