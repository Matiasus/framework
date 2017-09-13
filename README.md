# Framework PHP
Simple PHP framework with Dependency Injection pattern. Preview of functional framework can be seen on web site [poznamkovyblog](http://poznamkovyblog.cekuj.net). 
## Static classes
At the beginning are called five static classes about which is supposed that will be the only one in whole application. These static classes are
- [Config](#config)
- [Route](#route)
- [Date](#date)
- [Cookie](#cookie)
- [Session](#session)
### Config
Class is responded for load and parse config ini file. It contains instance of parse class that decompose config file into array. Config variable can be called by two getter methods 
- ```get($key)``` - which return string stored under called key and throw exception if no exists 
- ```getArray($key)``` - return array stored under called key and throw exception if no exists
## Objects
- [Html](#html)
- [Form](#form)
### Html
Html tag creator with recognizing self closing tags. For example create *\<div\>* tag with attribute *id='id-div'* and content *'This is content!'* should be done with following code:
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
Another example is html tag without any content (self closing tag):
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
