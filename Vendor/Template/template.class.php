<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2015 
* 
* Autor:		Mato Hrinko
*	Datum:		07.12.2016 / update
*	Adresa:		http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiracia: 		
*
***/
namespace Vendor\Template;

// use
use \Vendor\Cookie\Cookie as Cookie,
    \Vendor\Config\File as Config,
    \Vendor\Route\Route as Route,
    \Vendor\Date\Date as Date;

class Template{

	/** @var Object \Vendor\User\User */
  private $user;

  /** @var Object \Vendor\Buffer\Buffer */
  private $buffer;

	/** @var Object \Vendor\Template\Replace */
	private $replace;

  /** @var Object \Vendor\Database\Database */
  private $database;

	/** @var Object - Instance of given controller */
	private $controller;

	/** @var Array */
	private $config = array();

  /** @var String whole page content */
  private $page = '';

  /** @var Array premenne */
  private $variables = array();

  /** @var String directory */
  private $directory;

  /** @var String basic layout */
  private $layout_path;

  /** @var String address to content of page */
  private $content_path;


	/***
   * Constructor
   *
   * @param Object \Vendor\Di\Container
	 * @return Void
	 */
	public function __construct(\Vendor\User\User $user,
                              \Vendor\Buffer\Buffer $buffer,
                              \Vendor\Template\Replace $replace,
                              \Vendor\Database\Database $database,
                              \Vendor\Controller\Icontroller $controller)
	{
    // @var \Vendor\User\User
    $this->user = $user;
    // @var Object \Vendor\Buffer\Buffer
    $this->buffer = $buffer;
    // @var \Vendor\Template\Replace
    $this->replace = $replace;
    // @var \Vendor\Database\Database
    $this->database = $database;
    // @var \Vendor\Controller\Icontroller
    $this->controller = $controller;
    // basic directory address
    $this->directory = 'Application/'.Config::get('TEMPL', 'MODUL');

   // check if module set
    if (strlen(Config::get('TEMPL', 'MODUL')) > 0) {
      // join /
      $this->directory .= '/'.ucfirst(Route::get('module')).
                          '/'.Config::get('TEMPL', 'VIEWS');
    }
    // replacements
    $this->replacements();
	}

	/***
	 * Predpriprava na spracovanie sablony
	 *
	 * @param Void
	 * @return Void
	 */
	protected function replacements()
	{
		// path to basic layout
		$this->layout_path = $this->directory.
                         '/'.Config::get('TEMPL', 'LA_BAS');
    // content for layout
	  $this->content_path = $this->directory.
                          '/'.Route::get('controller').
                          '/'.Route::get('view').'.tpl.php';
    // load basic layout
    $this->page = $this->replace
                       ->layout($this->layout_path);
    // replace title
    $this->page = $this->replace
                       ->title(Config::get('TEMPL', 'RE_TIT'), 
                               Config::get('TEMPL', 'TITLE'), 
                               $this->page);

    // replace conntent
    $this->page = $this->replace
                       ->content($this->buffer,
                                 Config::get('TEMPL', 'RE_CON'),
                                 $this->content_path,
                                 $this->page);
    // replace forms
    $this->page = $this->replace
                       ->forms($this->controller,
                               Config::get('TEMPL', 'RE_FOR'),
                               $this->page);

    // replace conntent
    $this->page = $this->replace
                       ->flash(Config::get('TEMPL', 'RE_FLA'),
                               $this->page);
/*
												   'Editor',
                           'Javascript',

*/
	
  }

	/***
	 * Render content
	 *
	 * @param Void
	 * @return Void
	 */
	public function render()
	{
    // show content
		echo $this->page;
	}
}


