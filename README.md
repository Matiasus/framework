# Framework PHP
Simple PHP framework with Dependency Injection pattern. Preview of functional framework can be seen on web site [poznamkovyblog](http://poznamkovyblog.cekuj.net).
## Objects
### Html
Html tag creator with recognizing self closing tags. For example create *\<div\>* tag with attribute *id='id-div'* and content 'This is content!' should be done with following code:
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
Another example is html tag without any content (self closing tags):
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
Simple html form creator allows to create custimised html form element. Starts with creating a new Instance of class 
```php
// new instance of form tag
$html = new \Vendor\Form\Form();
```
Then must be defined form method (POST, GET)
```php
// set method
$form->setMethod('POST');
```
and form action
```php
// set action
$form->setAction('index.php');
```
Optionale parameter is inline form. It enables select between two visual forms (<i>inline form true</i> - more columns in one row and <i>inline form false</i> - one column for every row)
```php
// set display form - more columns in one row
$form->setInline(true);
```
Create element *text* should be done  
```php
// input text field
$form->input()
     ->text('name', 'Name')
     ->html5Attrs('required')
     ->create();
```
element *password*
```php
// input password field
$form->input()
     ->password('password', 'Pasword')
     ->html5Attrs('required')
     ->create();
```
element *checkbox*
```php
// input checkbox field
$form->input()
     ->checkbox('login', 'Remember', 'Remmember')
     ->create();
```
where arguments are defined in order *name, label, value, id and maxlength*. Finally html code is got by calling public method *getCode()*
```php
// get created html code     
$form->getCode();
```
