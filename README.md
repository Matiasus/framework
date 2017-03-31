<h1>Framework</h1>
Simple PHP framework trying to use Dependency Injection
<h2>Html</h2>
<h3>Example</h3>
<pre>
$html = new \Vendor\Html\Html();
$html->tag('div')
     ->attributes(array(
          'name'  => 'Name'),
          'value' => 'John'
     );
$html->compose();
</pre>
