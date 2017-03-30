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

class Html {

  /***
   * Constructor
   *
   * @param  Void
   * @return Void
   */
  public function __construct ()
  {
  }
  /***
   * 
   *
   * @param  Void
   * @return Void
   */
  public function create ($tag = false)
  {
    // check if non empty value
    if (func_num_args() > 1) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Too much arguments! Only <b>1</b> argument allowed!"); 
    }    
    // check if non empty value
    if (empty($tag)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Tag must be <b>NON</b> empty!"); 
    }
    // check if string
    if (!is_string($class)) {
      // throw to exception with error message
      throw new \Exception("[".get_called_class()."]:[".__LINE__."]: Tag must be a <b>string</b>!"); 
    }
    
  }  
}
