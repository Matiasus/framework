<?php

namespace Vendor\Form;

// use
use \Vendor\Config\File as Config;

class Composer {
  
  /** @var \Vendor\Html\Html */
  private $html;

  /** @var String */
  private $content;

  /** @var Array */
  private $code = array();
  
  /** @var Array */
  private $attributes = array();

  /** @var Array */
  private $parameters = array();

  /***
  * Constructor
  *
  * @param 
  * @return Void
  */
  public function __construct(\Vendor\Html\Html $html)
  {
    // @var \Vendor\Html\Html
    $this->html = $html;
    // parameters of form
    $this->parameters;
  }

  /***
  * 
  *
  * @param 
  * @return 
  */
  public function setParameters($parameters = array())
  {
    // set parameters
    $this->parameters = $parameters;
  }

  /***
  * 
  *
  * @param 
  * @return 
  */
  public function setContent($content)
  {
    // set content
    $this->content = $content;
  }

  /***
  * 
  *
  * @param 
  * @return 
  */
  public function setAttributes($attributes = array())
  {
    // set attributes
    $this->attributes = $attributes;
  }

  /***
  * 
  *
  * @param 
  * @return 
  */
  public function getAttribute($attribute)
  {
    // not empty?
    if (!empty($attribute)) {
      // if exists
      if (array_key_exists($attribute, $this->attributes)) {
        // return value
        return $this->attributes[$attribute];
      }
    }
    // 
    return false;
  }

  /***
  * 
  *
  * @param 
  * @return 
  */
  public function getParameter($parameter)
  {
    // not empty?
    if (!empty($parameter)) {
      // if exists
      if (array_key_exists($parameter, $this->parameters)) {
        // return value
        return $this->parameters[$parameter];
      }
    }
    return null;
  }

  /***
  * 
  *
  * @param 
  * @return 
  */
  public function getCode()
  {
    // get html code
    return $this->code;
  }
  /***
  * Html code
  * 
  * @param Void
  * @return String
  */
  public function create()
  {
    // name
    $name = $this->getAttribute(Config::get('FORM', 'NAME'));
    // content
    $content = $this->getAttribute(Config::get('FORM', 'LABEL'));
    // name must be set
    if (empty($name)) {
      // throw to exception with error message
      throw new \Exception('['.get_called_class().']:['.__LINE__.']: Composer can\'t store created html code! In config file misses FORM-NAME!');
    }
    // key
    $key = strtolower($name);
    // check if id is set
    if($this->getAttribute('id') === false) {
      // create id
      $this->attributes['id'] = 'id-'.$key;
    }
    // td element
    $td = $this->html->tag('td')
               ->attributes()
               ->content($content)
               ->create();
    // input element
    $input = $this->html->tag('input')
                  ->attributes($this->attributes)
                  ->create();
    // inline form
    if ($this->getParameter(Config::get('FORM', 'INLINE')) !== true) {
      // tr element
      $tr  = $this->html->tag('tr')->attributes()->content($td)->create();
      // td element
      $td  = $this->html->tag('td')->attributes(array("align"=>"right"))->content($input)->create();
      // tr element
      $tr .= $this->html->tag('tr')->attributes()->content($td)->create();       
    } else {
      // td element
      $td  = $this->html->tag('td')->attributes()->content($content)->create();
      // td element
      $td .= $this->html->tag('td')->attributes(array("align"=>"right"))->content($input)->create();
      // tr element
      $tr  = $this->html->tag('tr')->attributes()->content($td)->create();
    }
    // final code
    $this->code[$key] = $tr."\n";
    
/*  
echo "\n<br/>-------------------------------------------------<br/>\n";
echo  $table;
echo "\n<br/>-------------------------------------------------<br/>\n";

  

    $this->code  = ($this->params[Config::get('FORM', 'INLINE')] === true) ? "\n\t   <tr><td>" : "\n\t   <label for='id-".strtolower($this->params[Config::get('FORM', 'NAME')])."'>" ;
    $this->code .= $this->params[Config::get('FORM', 'LABEL')].(($this->params[Config::get('FORM', 'REQUIRED')] != '') ? '*' : '');
    $this->code .= (($this->params[Config::get('FORM', 'INLINE')] === true) ? "</td><td>" : "</label><br/>" );
    $this->code .= "\n\t    <input type='".$this->selector."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'ID')])) ? "" : " id='id-".strtolower($this->params[Config::get('FORM', 'NAME')])."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'ROWS')])) ? "" : " rows='".$this->params[Config::get('FORM', 'ROWS')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'COLS')])) ? "" : " cols='".$this->params[Config::get('FORM', 'COLS')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'VALUE')])) ? "" : " value='".$this->params[Config::get('FORM', 'VALUE')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'CLASS')])) ? "" : " class='".$this->params[Config::get('FORM', 'CLASS')]."'";
    $this->code .= (empty($this->params[Config::get('FORM', 'MAXLEN')])) ? "" : " maxlength='".$this->params[Config::get('FORM', 'MAXLEN')]."'";
    // check if no empty
    if (!empty($this->params[Config::get('FORM', 'PARAMS')])) {
      // check if array
      if (is_array($this->params[Config::get('FORM', 'PARAMS')])) {
        // loop through options
        foreach ($this->params[Config::get('FORM', 'PARAMS')] as $key => $value) {
          // append supplement options
          $this->code .= " ".$key."='".$value."' "; 
        }
      }
    }   
    $this->code .= "/>";
    $this->code .= (($this->params[Config::get('FORM', 'INLINE')] === true) ? "</td></tr>" : "<br/>");

*/
  }
}
