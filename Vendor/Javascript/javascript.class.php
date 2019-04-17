<?php

Namespace Vendor\Javascript;

class Javascript {

  private $functions = array();

  /***
   * Constructor
   *
   * @param  \Vendor\Config\Parser
   * @return Void
   */
	public function __construct()
	{
	}

  /***
   * 
   *
   * @param  Void
   * @return Void
   */
	public function setFunction($key) 
  {
    if (is_scalar($key)) {
      // zapis javascript parametrov
      return $this->functions[$key] = new \Vendor\Javascript\Parameters();
    }

	}

	/***
	 * CKEditor
	 *
	 * @param Void
	 * @return Object | Array of Objects
	 */
	public function getFunction($key = false)
  {
    if ($key) {
      return $this->functions[$key];
    } else {
      return $this->functions;
    }
	}

}

