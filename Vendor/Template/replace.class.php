<?php

namespace Vendor\Template;

use \Vendor\Session\Session as Session;

/** @class Replace template */
class Replace {

	/** @var Object \Vendor\Errors\Errors	*/
	private $errors;

	/** @var Object Instance of controller	*/
	private $controller;

	/***
	 * Constructor
	 *
	 * @param \Vendor\Errors\Errors
	 * @return Void
	 */
	public function __construct()
	{
	}

	/***
	 * Load includes
	 *
	 * @param String
	 * @param String
	 * @param String
	 * @return Void
	 */
	public function includes($searched, $replace, $content)	
	{
		// replace title
		return str_replace($searched, $replace, $content);
	}

	/***
	 * Load layout
	 *
	 * @param String - path to layout
	 * @return Void
	 */
	public function layout($path) 
	{
		// check if file exists
		if (!file_exists($path)) {
      // error message
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Layout <b>\''.$path.'\'</b> does not exists!');
      // unsuccess return
      return false;
    }
    // check if layout not empty
		if (empty($content = file_get_contents($path))) {
      // error message
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Empty layout <b>\''.$path.'\'</b>!');
      // unsuccess return
      return false;
    }
    // content <= layout
  	return $content;
	}

	/***
	 * Content replacement
	 *
	 * @param Object \Vendor\Buffer\Buffer
	 * @param String
	 * @param String
	 * @return Void
	 */
	public function content($buffer, $searched, $path, $page)
  {
		// check if file exists
		if (!file_exists($path)) {
      // error message
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Content <b>\''.$path.'\'</b> does not exists!');
    }
    // set path to buffered file content
    $buffer->setPath($path);
    // buffered content
    $replace = $buffer->getBufferContent();
    // return replaced content
    return $this->includes($searched, $replace, $page);
	}

	/***
	 * Title replacement
	 *
	 * @param String
	 * @param String
	 * @param String
	 * @return Void
	 */
	public function title($searched, $replace, $content)
	{
		// Title replace
		return $this->includes($searched, $replace, $content);
	}

	/***
	 * Errors replacement
	 *
	 * @param String
	 * @param String
	 * @param String
	 * @return Void
	 */
	public function errors($searched, $replace, $content)
	{
		// Error replace
		return $this->includes($searched, $replace, $content);
	}

	/***
	 * Flash - vypis flash spravy
	 *
	 * @param Object \Vendor\Session\Session
	 * @param String
	 * @param String
	 * @return Void
	 */
	public function flash($searched, $page)
	{
    // flash messages
    $key = 'flash';
    // get all flash messages 
    $messages = Session::get('flash');
    // check if array or object
		if (is_array($messages) || 
        is_object($messages))
		{
      // contnent of flash messages
      $messages = $this->errors->toString($messages);
		} 
    // session destroy
  	Session::destroy($key, false);
    // return replace content of flash message  
  	return $this->includes($searched, $messages, $page);
  }

	/***
	 * Replace and load form
	 *
   * @param Object - Instance of given controller
	 * @param String
	 * @param String
	 * @return Void
	 */
	public function forms($controller, $searched, $content)
	{
    // instance of controller
    $this->controller = $controller;
		// Replace {fromular meno_formulara} by html code
		return $this->content = preg_replace_callback($searched, array($this, "replaceForm"), $content);
	}

	/***
	 * Form callback - load form
	 *
	 * @param Array
	 * @return Void
	 */
	protected function replaceForm($matches)
	{
		// Name of form replaced
		$form = $matches[1];
		// Controller method
		$method = 'form' . ucfirst($form);
    // all methods in given controller
    $methods = get_class_methods($this->controller->getFullName());
    // check if method exists
		if(!in_array($method, $methods)) {
      // error message
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Form method <b>\''.$method.'\'</b> not present in controller '.$this->controller->getFullName().' !');
    }

    $this->controller->getInstance()->$method();
/*
			// Metoda triedy (napr. formControllera) musi vratit Object Form
			// $controller => napr. formController
			// $method => napr. formKomentar
			try	{
				return $this->objectController->$method()->getFormContent($form);
			}	
      catch(Exception $exception)	{
				print $exception->getMessage();
			}
		}
*/
	}


	/***
	 * Editor - vykreslenie editoru
	 *
	 * @param Void
	 * @return Void
	 */
	protected function Editor()
	{
		/* Obsah, ktory sa ma nahradit */
		$Editor = new \Vendor\Editor\Editor();

		if ((strpos($this->content, self::EDITOR) > 0))
		{
			/* Vymazanie znacky v sablone */
			$this->content = str_replace(self::EDITOR, "", $this->content);
			/* Nahradenie znacky v hlavnej sablone */
			$this->content = str_replace(self::BODY, $Editor->ckeditor() . "\n</body>", $this->content);
		}
	}

	/***
	 * Konstruktor vytvorenia spojenia s registrom
	 *
	 * @param Void
	 * @return Void
	 */
	public function setJavascript($script)
	{
    $this->javascript = $script;
	}

	/***
	 * Javascript
	 *
	 * @param Void
	 * @return Void
	 */
	protected function Javascript()
	{
    $join_delimiter = "','";
    $javascript_string = "";

    if (is_object($this->registry->javascript) &&
        is_array ($functions = $this->registry->javascript->getFunction()))
    {
      $javascript_string .= "\n  <script type=\"text/javascript\">\n";
      // load functions saved in registry
      foreach ($functions as $function => $parameters) {
        // start with name of function
        $javascript_string .= "    " . $function . "('";
        // check if function has parameters
        if (is_array($params = $parameters->getParameters())) {
          // load parameters of function
          foreach ($params as $parameter) {
            // connect parameters with function
            $javascript_string .= $parameter . $join_delimiter;
          }
        }
        // trim last 3 chars $join_delimiter
        $javascript_string = substr($javascript_string, 0, strlen($javascript_string) - strlen($join_delimiter)) . "');\n";
      }
      // replace </body> => <script>...functions(parameters)...</script>
      $javascript_string .= "  </script>\n</body>";
    }
    if ($javascript_string != "") {
      // Nahradenie znacky javascriptu javascriptom
      $this->content = str_replace(self::BODY, $javascript_string, $this->content);
    }
  }

	/***
	 * Spracovanie sablony
	 *
	 * @param Void
	 * @return Void
	 */
	public function render($content)
	{
		echo $content;
	}

}


