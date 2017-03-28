<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Input {

  /** @var \Vendor\Form\Form */
  private $form;

  /** @var Array */
  private $params = array();

	/***
	 * Constructor
	 *
	 * @param Void
	 * @return Void
	 */
	public function __construct(\Vendor\Form\Form $form)
  {
    // @var \Vendor\Form\Form
    $this->form = $form;
	}

	/***
	 * 
	 *
	 * @param 
	 * @return 
	 */
  public function text($name = false, $label = false, $value = false, $maxlen = false)
  {
    // check number of arguments
    if (func_num_args() > 4) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Maximum arguments is <b>4</b>!');
    }
    // parameters for input
    $this->params = array(
      Config::get('FORM', 'REQUIRED') => false,
      Config::get('FORM', 'INLINE')   => Config::get('FORM', 'VAL_INLINE'),
      Config::get('FORM', 'NAME')     => $name,
      Config::get('FORM', 'LABEL')    => $label,
      Config::get('FORM', 'VALUE')    => $value,
      Config::get('FORM', 'MAXLEN')   => $maxlen
    );
    // @var \Vendor\Form\Input\Text
    return new \Vendor\Form\Input\Text($this->form, $this->params);
  }
}

