<?php

namespace Vendor\Template;

use \Vendor\Route\Route as Route,
    \Vendor\Session\Session as Session;

/** @class Replace template */
class Replace {

	/** @var Object \Vendor\Errors\Errors	*/
	private $errors;

	/** @var Object Instance of controller	*/
	private $controller;

	/***
	 * @desc    Constructor
	 *
	 * @param   Void
   *
	 * @return  Void
	 */
	public function __construct()
	{
	}

	/***
	 * @desc    Load includes
	 *
	 * @param   String
	 * @param   String
	 * @param   String
   *
	 * @return  Void
	 */
	public function includes($searched, $replace, $content)	
	{
		// replace title
		return str_replace($searched, $replace, $content);
	}

	/***
	 * @desc    Load layout
	 *
	 * @param   String - path to layout
   *
	 * @return  Void
	 */
	public function layout($path) 
	{
		// check if file exists
		if (!file_exists($path)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Layout <b>\''.$path.'\'</b> does not exists!');
    }
    // check if layout not empty
		if (empty($content = file_get_contents($path))) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Empty layout <b>\''.$path.'\'</b>!');
    }
    // content <= layout
  	return $content;
	}

	/***
	 * @desc    Content replacement
	 *
	 * @param   \Vendor\Buffer\Buffer
	 * @param   String
	 * @param   String
   *
	 * @return  Void
	 */
	public function content($buffer, $searched, $path, $page)
  {
		// check if file exists
		if (!file_exists($path)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Content <b>\''.$path.'\'</b> does not exists!');
    }
    // set path to buffered file content
    $buffer->setPath($path);
    // buffered content
    $replace = $buffer->getBufferContent();
    // return replaced content
    return $this->includes($searched, $replace, $page);
	}

	/***
	 * @desc    Title replacement
	 *
	 * @param   String
	 * @param   String
	 * @param   String
   *
	 * @return  Void
	 */
	public function title($searched, $replace, $content)
	{
		// Title replace
		return $this->includes($searched, $replace, $content);
	}

	/***
	 * @desc    Flash - show flash message
	 *
	 * @param   String
	 * @param   String
   *
	 * @return  Void
	 */
	public function flash($searched, $page)
	{
    $key = 'flash';
    // replace messages
    $replace = '';
    // get all flash messages 
    $messages = Session::get($key);
    // check if array
    if (is_array($messages)) {
      // loop
      foreach ($messages as $message) {
        // append messages
        $replace .= $message."<br/>\n";
      }
		} else {
      // append messages
      $replace .= $messages."<br/>\n";
    }
    // session destroy
  	Session::destroy($key, false);
    // return replace content of flash message  
  	return $this->includes($searched, $replace, $page);
  }

	/***
	 * Replace and load form
	 *
   * @param Object - Instance of given controller
	 * @param String
	 * @param String
   *
	 * @return Void
	 */
	public function forms($controller, $searched, $content)
	{
    // instance of controller
    $this->controller = $controller;
		// Replace {fromular meno_formulara} by html code
		return preg_replace_callback($searched, array($this, "replaceForm"), $content);
	}

	/***
	 * @desc    Form callback - load form
	 *
	 * @param   Array
	 * @return  Void
	 */
	protected function replaceForm($matches)
	{
		// Name of form replaced
		$form = $matches[1];
		// Controller method
		$method = 'form' . ucfirst($form);
    // all methods in given controller
    $methods = get_class_methods(get_class($this->controller));
    // check if method exists
		if(!in_array($method, $methods)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Form method <b>\''.$method.'\'</b> not present in controller '.$this->controller->getFullName().' !');
    }
		// html code
    return $this->controller->$method();
	}

  /***
   * @desc    Replace javascript
   *
   * @param   Void
   * @return  Void
   */
  public function javascript($searched, $content)
  {
    $replace = '<script type="text/javascript">';
    $replace .= '';
    $replace .= '</script>';
    // return replace content of flash message  
    return $this->includes($searched, $replace, $content);
  }

	/***
	 * Editor - vykreslenie editoru
	 *
	 * @param Void
	 * @return Void
	 */
	protected function editor()
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

}


