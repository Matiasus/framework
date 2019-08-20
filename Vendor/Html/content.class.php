<?php
/***
 * POZNAMKOVYBLOG Copyright (c) 2015 
 * 
 * Autor:        Mato Hrinko
 * Datum:        30.03.2017
 * Adresa:       http://poznamkovyblog.cekuj.net
 * 
 * ------------------------------------------------------------
 * Description:
 *
 ***/

namespace Vendor\Html;

class Content {

  /** @var \Vendor\Html\Composer */
  private $composer;

  /***
   * Constructor
   *
   * @param  
   * @return Void
   */
  public function __construct (\Vendor\Html\Composer $composer)
  {
    // @var \Vendor\Html\Composer
    $this->composer = $composer;
  }
 
  /***
   * 
   *
   * @param  String
   * @return Void
   */
  public function content ($content)
  {
    // set content
    $this->composer->setContent($content);
    // @var \Vendor\Html\Composer
    return $this->composer;
  }

  /***
   * Compose html code of given tag
   *
   * @param  Void
   * @return Void
   */
  public function create ()
  {
    // compose html code
    return $this->composer->create();
  }
}
