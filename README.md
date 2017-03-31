<h1>Framework</h1>
<p>Simple PHP framework trying to use Dependency Injection</p>
<h2>Html</h2>
<p>Instance of class creates html tag.</p>
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
