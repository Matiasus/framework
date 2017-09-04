# Framework PHP
Simple PHP framework with Dependency Injection pattern. Preview of functional framework can be seen on web site [poznamkovyblog](http://poznamkovyblog.cekuj.net).
## Objects
### Html
This instance creates html tags. Class doesn not check proper attributes for given html tag according to **w3c**.(for example paragraph tag *p* has only one attribute *align* according to [w3c](https://www.w3schools.com/tags/tag_p.asp)) but recognizes self closing tags.
For example create div **<tag>** with attribute id *'id-div'* and with content 'This is content!' should be done with following code
```php
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// table element
$html->tag('div')
     ->attributes(array(
          'id'=>'id-div'))
     ->content('This is content!')
     ->create();
```
```html
<div id='id-div>This is content!</div>
```
Example of creating html tag that contain attributes without content can be done by following code
```php
// table element
$html->tag('input')
     ->attributes(array(
          'type'=>'text'))
     ->create();
```
