<?php

namespace Application\Module\Admin\Controller;

class ubuntuController extends \Vendor\Controller\Controller {

  /** @var \Application\Module\Front\Model\Model */
  private $model;

  /***
  * Constructor
  *
  * @param  Object \Application\Module\Front\Model\Model
  * @return Void
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
    $this->variables = $this->model->showCategoryArticles();
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
    $this->variables = $this->model->showDetailArticle();
  }
}
