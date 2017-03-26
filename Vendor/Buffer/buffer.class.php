<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2016 
* 
* Autor:		Mato Hrinko
*	Datum:		12.7.2015
*	Adresa:		http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Inspiracia: 		
*
***/
namespace Vendor\Buffer;

/** @var Class Buffer */
class Buffer {

	/** @var Object \Vendor\Errors\Errors */
  private $errors;

  /** @var String */
	private $path = '';

  /***
   * Constructor
   *
   * @param String
   * @return Void
   */
	public function __construct() 
	{
	}

  /***
   * Set path to file
   *
   * @param String
   * @return Void
   */
	public function setPath($path) 
	{
    // path to file
    $this->path = $path;
  }

  /***
   * Store buffered content
   *
   * @param Void
   * @return Void
   */
	public function getBufferContent() 
	{
    // overenie existencie banky a dlzky nazvu
    if (!isset($this->path)) {
      // throw to exception
      throw new \Exception('\\'.get_class($this).' -> '.ucfirst(__FUNCTION__).' ( ) [Line: '.__LINE__.']: Can\'t buffer content from file <b>'.$this->path.'</b>');
    }
    // start buffering
		ob_start();
    // include buffered file
		require_once $this->path;
    // buffered cntent
		$buffer = ob_get_contents();
    // exit buffering
		ob_end_clean();
    // return buffered content
    return $buffer;
  }
}
