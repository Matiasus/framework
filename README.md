# Framework PHP
Simple PHP framework with Dependency Injection pattern. Preview of functional framework can be seen on web site [poznamkovyblog](http://poznamkovyblog.cekuj.net). 
## Static classes
At the beginning are called five static classes about which is supposed that will be the only one in whole application. These static classes are
- [Config](#config)
- [Route](#route)
- [Cookie](#cookie)
- [Session](#session)
### Config
Class is responded for load and parse config ini file. It contains instance of parse class that decompose config file into array. Config variable can be called by two getter methods 
- ```get($key)``` - which return string stored under called key and throw exception if no exists 
- ```getArray($key)``` - return array stored under called key and throw exception if no exists
### Route
This static class parses url requests and stores it into variables that can be used for next purpose. There are defined **public** methods:
- `get ($key = false, $exception = false)` - get parameter (*module, controller, view, param1, param2, process, operation*), names of parameters (module, ...) are defined in **config.php.ini**
- `getSerNameUri ($http = false)` - url in form `www.link.com/show/ubuntu`, 
- `getfullUri ($http = false)` - url in form `www.link.com`
- `getReqUri ()` - url in form `show/ubuntu/?call=script`
### Cookie
Simplify static cookie class is responsible for manipulation with COOKIES. It contains two methods needed for store and destory COOKIE
- ```set($name, $value, $expire, $path = "/", $domain = false)``` - set COOKIE under specific name
- ```get($key = false, $exception = false)``` - destroy COOKIE with specific name, throw to exception if no name COOKIE exists
### Session
Session class is used for manipulation with SESSION variables. It contains two public static methods
- ```set($key = false, $value = false, $regenerate = false)```
- ```get($key = false, $exception = false)```
## Objects
- [Html](#html)
- [Form](#form)
### Html
Html class creates html tag with recognizing self closing tags. For example tag *\<div\>* with attribute *id='id-div'* and with content *'This is content!'* should be created with following piece of code:
```php
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// div element
$html->tag('div')
     ->attributes(array(
       'id'=>'id-div'))
     ->content('This is content!')
     ->create();
```
```html
<div id='id-div>This is content!</div>
```
Another example creates self closing html tag:
```php
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// input element
$html->tag('input')
     ->attributes(array(
      'type'=>'text'))
     ->create();
```
```html
<input type='text' \>
```
### Form
Simple html form creator allows to create custimised html form element. For example create form with two inputs (text, password) and submit should by done by following piece of code:
```php
// new instance of form tag
$html = new \Vendor\Form\Form();
// set method
$form->setMethod('POST');
// set action
$form->setAction('index.php');
// set display form - more columns in one row
$form->setInline(true);
// input text field
$form->input()
     ->text('name', 'Name')
     ->html5Attrs('required')
     ->create();
// input password field
$form->input()
     ->password('password', 'Password')
     ->html5Attrs('required')
     ->create();
// submit
$form->input()
     ->submit('submit', '', 'Login')
     ->create();
```
Arguments of form elements are defined in order *name*, *label*, *value*, *id* and *maxlength*. Html code of form can be get by calling public method *getCode()*
```php
// get created html code     
$form->getCode();
```
which generate following html code
```html
<form action='index.php' method='POST'>
  <table>
   <tr><td>Name*</td><td> <!-- label -->
    <input type='text' name='Name' id='id-name' value='' required /></td></tr>
   <tr><td>Password*</td><td> <!-- label -->
    <input type='password' name='Password' id='id-password' value='' required /></td></tr>
   <tr><td>
    <input type='submit' name='submit' value='Login' /></td></tr>
  </table>
</form>
```
