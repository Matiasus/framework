<?php

namespace Application\Module\User\Controller;

class articlesController extends \Vendor\Controller\Controller {

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
    $this->model->showArticles();
    $this->variables['privileges'] = 'user';

    print_r($_SESSION);
  }

  /***
   * Render - logon
   *
   * @param Void
   * @return Void
   */	
  public function renderPrihlasenie()
  {
  }


}
