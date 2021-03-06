<?php

namespace Application\Module\Front\Controller;

class formController extends \Vendor\Controller\Controller {

  /** @var \Application\Module\Front\Model\Model */
  public $model;

  /***
   * @desc    Constructor
   *
   * @param   \Application\Module\Front\Model\Model
   * @return  Void
   */
  public function __construct(\Application\Module\Front\Model\Model $model)
  {
    // @var \Application\Module\Front\Model\Model
    $this->model = $model;
  }

  /***
   * @desc    Render - default
   *
   * @param   Void
   * @return  Void
   */
  public function renderDefault()
  {
    // session login
    $this->model->sessionLogin();
  }

  /***
   * @desc    Render logon
   *
   * @param   Void
   * @return  Void
   */	
  public function renderPrihlasenie()
  {
  }

  /***
   * @desc    Form logon
   *
   * @param   Void
   * @return  Void
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
   * @desc    Render - activation
   *
   * @param   Void
   * @return  Void
   */	
  public function renderAktivacia()
  {
    // process activation
    $this->model->activation(); 
  }

  /***
   * @desc    Render registration
   *
   * @param   Void
   * @return  Void
   */
  public function renderRegistracia()
  {
  }

  /***
   * @desc    Form - registration
   *
   * @param   Void
   * @return  Void
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
   * @desc    Form - callback
   *
   * @param   Array
   * @return  Void
   */
  private function registraciaProccess($form)
  {
    // process registration
    $this->model->registration($form); 
  }
}
