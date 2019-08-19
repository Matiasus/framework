<?php

namespace Application\Module\Admin\Controller;

class sportsController extends \Vendor\Controller\Controller {

  /** @var \Application\Module\Front\Model\Model */
  private $model;

  /***
   * @desc    Constructor
   *
   * @param   Object \Application\Module\Front\Model\Model
   * @return  Void
   */
  public function __construct(\Application\Module\Admin\Model\Model $model)
  {
    // @var \Application\Module\Front\Model\Model
    $this->model = $model;
  }

  /***
   * Render - default
   *
   * @param Void
   * @return Void
   */
  public function renderDefault()
  {
    // show articles
    $this->variables = $this->model->showSportRun();
  }

  /***
   * Render - detail
   *
   * @param Void
   * @return Void
   */
  public function renderDetail()
  {
    // show articles
    $this->variables = $this->model->showSportRunDetail();
  }

  /***
   * Render - add
   *
   * @param Void
   * @return Void
   */
  public function renderAddtime()
  {
    // show articles
    $this->variables = $this->model->addtimeSportsRun();
  }

  /***
   * @desc    Form add time
   *
   * @param   Void
   * @return  Void
   */	
  public function formAddtime()
  {
    // show created form
    $form = $this->model->showFormAddtime(new \Vendor\Form\Form(new \Vendor\Html\Html));
    // return html code
    return $form->getCode();
  }
}
