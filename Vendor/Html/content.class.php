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
  
  /***
   * 
   *
   * @param  String
   * @return Void
   */
  public function setContent ($content)
  {
    if (!is_string($content)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Content must be a <b>string</b>!");       
    }
    // set content to html instance
    $this->html->setContent($content);
  }  
}
