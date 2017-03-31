<h1>Framework</h1>
<p>Simple PHP framework with Dependency Injection</p>
<h2>Html</h2>
<p>Instance of class creates html tag. Class doesn't check proper attributes for given html tag. It only recognize self closing tags.</p>
<h3>Example</h3>
<pre>
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// set attributes and cotent
$html->tag('div')
     ->setAttrs(array(
          'class' => 'test',
          'align' => 'center')
     ->setContent('This is my first div element!');
// compose html tag
$html->compose();
</pre>
