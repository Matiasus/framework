<?php

namespace Application\Module\Front\Controller;

class formController {

  /** @var \Application\Module\Front\Model\Model */
  public $model;

  /***
  * Constructor
  *
  * @param  Object \Application\Module\Front\Model\Model
  * @return Void
  */
  public function __construct(\Application\Module\Front\Model\Model $model)
  {
    // @var \Application\Module\Front\Model\Model
    $this->model = $model;
  }

  /***
   * Render default
   *
   * @param Void
   * @return Void
   */
  public function renderDefault()
  {
    // check if persistent login on
    $this->model->autoLogon();
  }

  /***
   * 
   *
   * @param Void
   * @return Void
   */	
  public function renderPrihlasenie()
  {
  }

  /***
   * Form logon
   *
   * @param Void
   * @return Void
   */	
  public function formPrihlasenie()
  {
    return $this->model->showFormPrihlasenie(new \Vendor\Form\Form());
    /*
    $form->addPassword('Passwordname', 'Heslo/Password', '')->setRequired();
    $form->addCheckbox('Persistentlog', 'Pamataj', 'Ostať prihlásený');
    $form->addSubmit('submit', 'Prihlásiť');

    // Nastavenie tabulky, s ktorou sa ma pracovat
    $this->registry->mysql->setTable(self::USERS);

    // Validacia zadanych nazvov jednotlivych prvkov formulara
    // ci sa zhoduju s nazvami stlpcov prislusnej tabulky v MySQL
    if ($form->succeedSend()) {
    // callback logon
      $this->prihlasenieProccess($form);
    }
    return $form;
    */
  }

  /***
  * Callback - logon
  *
  * @param Array
  * @return Void
  */	
  private function prihlasenieProccess($form) 
  {
    // Spracovanie prihlasenia
    $this->formprocess
         ->logon($form); 
  }

  /***
  * Render - activation
  *
  * @param Void
  * @return Void
  */	
  public function renderAktivacia()
  {
    // Spracovanie registracie
    $this->formprocess
         ->activation(); 
  }

  /***
  * Render registration
  *
  * @param Void
  * @return Void
  */
  public function renderRegistracia()
  {
  }

  /***
  * Form - registration
  *
  * @param Void
  * @return Void
  */
  public function formRegistracia()
  {

print_r($this->model->route);
    $form = new \Vendor\Form\Form();

    $form->setAction($this->model->route->getSerNameUri());
    $form->setInlineForm(false);

    $form->addEmail('Email', 'E-mail', '')->setRequired();
    $form->addText('Username', 'Meno/Name', '')->setRequired();
    $form->addPassword('Passwordname', 'Heslo/Password', '')->setRequired();
    $form->addSubmit('submit', 'Registrovať');

    /**
     * Nastavenie tabulky, s ktorou sa ma pracovat
     */
    $this->registry->mysql->setTable(self::USERS);

  /**
   * Validacia zadanych nazvov jednotlivych prvkov formulara
   * => ci sa zhoduju nazvy prvkov formulara s nazvami stlpcov prislusnej tabulky
   */
  if ($form->succeedSend() === true) {
    $this->registraciaProccess($form);
  }
  return $form;
  }

  /***
  * Form - callback
  *
  * @param Array
  * @return Void
  */
  private function registraciaProccess($form)
  {
    // Spracovanie registracie
    $this->formprocess->registration($form); 
  }
}
