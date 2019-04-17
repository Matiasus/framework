<?php

namespace Application\Module\Admin\Controller;

class articlesController extends \Vendor\Controller\Controller {

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
   * @desc   Render all articles 
   *
   * @param  Void
   * @return Void
   */
  public function renderDefault()
  {
    // show articles
    $this->variables = $this->model->showAllArticles();
  }

  /***
   * @desc   Render add article 
   *
   * @param  Void
   * @return Void
   */
  public function renderAdd()
  {
    // add article
    $this->variables = $this->model->addArticle();
  }

  /***
   * @desc    Form logon
   *
   * @param   Void
   * @return  Void
   */	
  public function formAdd()
  {
    // create form
    $form = $this->model
                 ->showFormAdd(new \Vendor\Form\Form(new \Vendor\Html\Html));
    // return html code
    return $form->getCode();
  }
}
