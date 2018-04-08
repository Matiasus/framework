<?php

namespace Application\Module\Admin\Controller;

class homeController extends \Vendor\Controller\Controller {

  /** @var \Application\Module\Front\Model\Model */
  private $model;

  /***
   * @desc    Constructor
   *
   * @param   Object \Application\Module\Front\Model\Model
   * @return  Void
   */
  public function __construct(\Application\Module\Admin\Model\Components $model)
  {
    // @var \Application\Module\Front\Model\Model
    $this->model = $model;
  }

  /***
   * @desc   Render all components
   *
   * @param  Void
   * @return Void
   */
  public function renderDefault()
  {
    // show components
    $this->variables = $this->model->showAllComponents(new \Vendor\Html\Html);
  }

  /***
   * @desc    Render 
   *
   * @param   Void
   * @return  Void
   */	
  public function renderAdd()
  {
    // add variables
    $this->variables = $this->model->formAddVariables();
  }

  /***
   * @desc    Form add component
   *
   * @param   Void
   * @return  Void
   */	
  public function formAdd()
  {
    // show created form
    $form = $this->model->showFormAdd(new \Vendor\Form\Form(new \Vendor\Html\Html));
    // return html code
    return $form->getCode();
  }
}
