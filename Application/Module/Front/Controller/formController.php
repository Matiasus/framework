<?php

namespace Application\Module\Front\Controller;

class formController extends \Vendor\Controller\Controller {

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
    // session login
    $this->model->sessionLogin();
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
    // create form
    $form = $this->model
                 ->showFormPrihlasenie(new \Vendor\Form\Form(new \Vendor\Html\Html));
    // return html code
    return $form->getCode();
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
    $this->model
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
    // create form
    $form = $this->model
                 ->showFormRegistracia(new \Vendor\Form\Form(new \Vendor\Html\Html));
    // return html code
    return $form->getCode();
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
    $this->model->registration($form); 
  }
}
