# Framework PHP
Simple PHP framework with Dependency Injection pattern. Preview of functional framework can be seen on web site [poznamkovyblog](http://poznamkovyblog.cekuj.net).
## Objects
### Html
This instance creates html tags. Class doesn not check proper attributes for given html tag according to **w3c**.(for example paragraph tag *p* has only one attribute *align* according to [w3c](https://www.w3schools.com/tags/tag_p.asp)) but recognizes self closing tags.
For example create **\<div\>** tag with attribute *id='id-div'* and with content 'This is content!' should be done with following code:
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
Another situation is when we want create html tag without any content (self closing tags). It should be simple create by code below, where is no present content method:
```php
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// input element
$html->tag('input')
     ->attributes(array(
          'type'=>'text'))
     ->create();
```
