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
    \Vendor\Date\Date as Date;

class Template{

	/** @var Object \Vendor\User\User */
  private $user;

	/** @var Object \Vendor\Route\Route */
  private $route;

	/** @var Object \Vendor\Errors\Errors */
  private $errors;

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
	public function __construct(\Vendor\Di\Container $container)
	{
    // @var \Vendor\User\User
    $this->user = $container->service('\Vendor\User\User');
    // @var \Vendor\Route\Route
    $this->route = $container->service('\Vendor\Route\Route');
    // @var Object \Vendor\Buffer\Buffer
    $this->buffer = $container->service('\Vendor\Buffer\Buffer');
    // @var \Vendor\Database\Database
    $this->database = $container->service('\Vendor\Database\Database');
    // controller object
    $this->controller = $container->service($this->route->get('controller_namespace'));


    // @var \Vendor\Template\Replace
    $this->replace = new \Vendor\Template\Replace();
    // basic directory address
    $this->directory = 'Application/'.Config::get('TEMPL', 'MODUL');

   // check if module set
    if (strlen(Config::get('TEMPL', 'MODUL')) > 0) {
      // join /
      $this->directory .= '/'.ucfirst($this->route->get('module')).
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
                          '/'.$this->route->get('controller').
                          '/'.$this->route->get('view').'.tpl.php';
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
												   'Forms',
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


