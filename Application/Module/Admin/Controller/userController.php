<?php

namespace Application\Module\Admin\Controller;

class userController extends \Vendor\Controller\Controller {

  /** @var \Application\Module\Front\Model\Model */
  private $model;

  /***
   * @desc    Constructor
   *
   * @param   Object \Application\Module\Front\Model\Model
   * @return  Void
   */
  public function __construct(\Application\Model\Model $model)
  {
    // @var \Application\Module\Front\Model\Model
    $this->model = $model;
  }

  /***
   * @desc   Logoff user
   *
   * @param  Void
   *
   * @return Void
   */
  public function renderLogoff()
  { 
    // logoff user
    $this->model->logoff();
  }
}
