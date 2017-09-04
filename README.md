# Framework PHP
Simple PHP framework with Dependency Injection pattern. Preview can be seen on web site [poznamkovyblog](http://poznamkovyblog.cekuj.net).
## Objects
### Html
Instance of class creates html tag. Class doesn't check proper attributes for given html tag. It only recognizes self closing tags.
Simple example of creating html tag starts with a new Instance of class *\Vendor\Html\Html*.
```php
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
```
and then should be created required elements with attributes and content
```php
// table element
$html->tag('div')
     ->attributes(array(
          'id'=>'id-div'))
     ->content('This is my first div container!')
     ->create();
```
or with attributes but without content
```php
// table element
$html->tag('input')
     ->attributes(array(
          'type'=>'text'))
     ->create();
```
Output of first example is
```html
<div id='id-div>This is my first div container</div>
```
