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
/*
		// Errors occured during previous code
		if (false === $this->errors->checkIfNoErrors())	{
      // load error layout
      $this->page = $this->replace->layout($this->errors->layout);
      // replace error title
      $this->page = $this->replace->title($this->config['TEMPLATE']['REPL_TITLE'], 
                                          $this->config['TEMPLATE']['ERROR'], 
                                          $this->page);
      // replace errors
      $this->page = $this->replace->errors($this->config['TEMPLATE']['REPL_ERROR'], 
                                           $this->errors->toString($this->errors->messages()), 
                                           $this->page);
      // return replacement
      return false;
		}
*/
    // replacements
    $this->replacements();
	}


	/***
	 * Variable set
	 *
	 * @param String, String - key, Value
	 * @return Void
	 */
	public function set($key, $value)
	{
    if (empty($key)) {
      // zapis chyby - error
      $this->errors->store('warning', '\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Variable must assign <b>key</b>!');
      // neuspesny navrat
      return false;
    }
    // ulozenie premennej
    $this->variables[$key] = $value;
	}

	/***
	 * Variable call
	 *
	 * @param String - key
	 * @return String | Array
	 */
	public function get($key, $errors = false)
	{
    // overenie pritomnosti kluca
		if ($key !== false) {
      // overi, ci je spravne zadana hodnota kluca
      if (empty($key)) {
        // zapis chyby - error
        $this->errors->store('warning', '\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Key has <b>Zero length</b> or <b>NULL</b> or <b>0</b>!');
        // neuspesny navrat
        return false;
      // overi existenciu pozadovaneho nastavenia
		  } else if (!array_key_exists($key, $this->variables)) {
        // zapis chyby - error
        $this->errors->store('warning', '\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Cookie <b>\''.$key.'\'</b> does not exists!');
        // neuspesny navrat
        return false;
      // nastavenie podla kluca existuje
      } else {
        // return variable
        return $this->variables[$key];
      }
    } 
    // return variables
    return $this->variables;
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
/*
    // replace warnings
    $this->page = $this->replace
                       ->errors($this->config['TEMPLATE']['REPL_ERROR'], 
                                $this->errors->toString($this->errors->messages('warning', false)), 
                                $this->page);
*/
    // replace conntent
    $this->page = $this->replace
                       ->content($this->buffer,
                                 Config::get('TEMPL', 'RE_CON'),
                                 $this->content_path,
                                 $this->page);


    // replace conntent
    $this->page = $this->replace
                       ->flash(Config::get('TEMPL', 'RE_FLA'),
                               $this->page);
/*
    // replace forms
    $this->page = $this->replace
                       ->forms($this->controller,
                               $this->config['TEMPLATE']['REPL_FORMS'],
                               $this->page);
/*
												   'Forms',
												   'Editor',
                           'Javascript',

*/
	
  }

	/***
	 * Spracovanie sablony
	 *
	 * @param Array - pole metod, ktore sa maju volat
	 * @return Void
	 */
	protected function process($methods = array())
	{
    // object with replace function
    $replace = new \Vendor\Template\Replace($this->container,
                                            $this->config);
		//  Nacitanie premennych do premennej $variables cim je mozne volat premnenne z prislusneho kontrolera
		$this->variables = $this->objectController->variables;
		// Prechazanie jednotlivych metod zadanych v poli
		foreach ($methods as $method)	{
      // overenie existecie metody
			if (method_exists($this, $method))	{
				// Volanie metod jednotlivych metod z template class
				$this->$method();
			} else {
        // zapis chyby
				$this->container->get('errors')->store('warning', '\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Method <b>\''.$method.'\'</b> does not exists!');
			}
		}
		// Vykreslenie obsahu, cize tlacenie premennej $this->content
		echo $this->render();
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
		$this->replace->render($this->page);
	}
}


