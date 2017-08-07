<?php

/***
* POZNAMKOVYBLOG Copyright (c) 2016 
* 
* Autor:          Mato Hrinko
* Datum:          12.7.2015
* Adresa:         http://poznamkovyblog.cekuj.net
* 
* ------------------------------------------------------------
* Description: 		
*
***/
namespace Vendor\Buffer;

/** @var Class Buffer */
class Buffer {

  /** @var String */
  private $path = '';
  
  /** @var String */
  private $buffer = '';  

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
    // is empty or not set?
    if ( empty($this->path) || 
        !isset($this->path)) {
      // throw to exception
      throw new \Exception('\\'.get_class($this).'[Line: '.__LINE__.']: Path to file is empty! Can\'t  load file!');
    }
    // is string?
    if (!is_string($this->path)) {
      // throw to exception
      throw new \Exception('\\'.get_class($this).'[Line: '.__LINE__.']: Path to file must be string!');
    }
    // is readable?
    if (!is_readable($this->path)) {
      // throw to exception
      throw new \Exception('\\'.get_class($this).'[Line: '.__LINE__.']: Can\'t read content from file <b>'.$this->path.'</b>. File is unreadable!');
    }
    // start buffering
    ob_start();
    // include buffered file
    require_once $this->path;
    // buffered cntent
    $this->buffer = ob_get_contents();
    // exit buffering
    ob_end_clean();
    // return buffered content
    return $this->buffer;
  }
}
