<?php

namespace Application\Module\Admin\Controller;

class componentsController extends \Vendor\Controller\Controller {

  /** @var \Application\Module\Admin\Model\Components */
  private $components_model;

  /***
   * @desc    Constructor
   *
   * @param   Object \Application\Module\Admin\Model\Components
   * @return  Void
   */
  public function __construct(\Application\Module\Admin\Model\Components $components_model)
  {
    // @var \Application\Module\Front\Model\Model
    $this->components_model = $components_model;
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
    $this->variables = $this->components_model->showAllComponents(new \Vendor\Html\Html);
  }

  /***
   * @desc   Render all articles 
   *
   * @param  Void
   * @return Void
   */
  public function renderCategory()
  {
    // show articles
    $this->variables = $this->components_model->showCategoryComponents();
  }
}
