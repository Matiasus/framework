<h1>Framework</h1>
     <p>Simple PHP framework with Dependency Injection</p>
<h2>Html</h2>
     <p>Instance of class creates html tag. Class doesn't check proper attributes for given html tag. It only recognizes self         closing tags.</p>
<h3>Example</h3>
<p>With content</p>
<p>
<pre>
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// table element
$html->tag('div')
     ->attributes(array(
          'id'=>'id-div'))
     ->content('This is my first div container!')
     ->create();
creates
\<div id='id-div'\>This is my first div container!\</div\>
</pre>
</p>
<p>or without content</p>
<p>
<pre>
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// table element
$html->tag('input')
     ->attributes(array(
          'type'=>'text'))
     ->create();
</pre>
</p>
