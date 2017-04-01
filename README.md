<h1>Framework</h1>
<p>Simple PHP framework with Dependency Injection</p>
<h2>Html</h2>
<p>Instance of class creates html tag. Class doesn't check proper attributes for given html tag. It only recognizes self closing tags.</p>
<h3>Example</h3>
<p>
<pre>
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// table element
$html->tag('table')
     ->attributes(array(
          'id'=>'table'))
     ->content("\n".$code)
     ->create();
</pre>
or
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
