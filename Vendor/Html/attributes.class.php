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

class Attributes {

  /** @var \Vendor\Html\Html $html */
  private $html;
      
  /***
   * Constructor
   *
   * @param  \Vendor\Html\Html $html
   * @return Void
   */
  public function __construct (\Vendor\Html\Html $html)
  {
    // @var \Vendor\Html\Html $html
    $this->html = $html;
  }
}
