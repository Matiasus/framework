<h1>Framework</h1>
<p>Simple PHP framework with Dependency Injection pattern</p>
<h2>Html</h2>
<p>Instance of class creates html tag. Class doesn't check proper attributes for given html tag. It only recognizes self closing tags.
Simple example of creating html tag starts with a new Instance of class <i>\Vendor\Html\Html</i>
<pre>
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
</pre>
and then should be created required elements with attributes and content
<pre>
// table element
$html->tag('div')
     ->attributes(array(
          'id'=>'id-div'))
     ->content('This is my first div container!')
     ->create();
</pre>
or with attributes but without content
<pre>
// new instance of simple html tag creator
$html = new \Vendor\Html\Html();
// table element
$html->tag('input')
     ->attributes(array(
          'type'=>'text'))
     ->create();
</pre>
Output of first example is
<pre>
&lt;div id='id-div'&gt;This is my first div container!&lt;/div&gt;
</pre>
and for second example is
<pre>
&lt;input type='text' \&gt;
</pre>
</p>
<h2>Form</h2>
<p>
Simple html form creator allows to create custimised html form element. Starts with creating a new Instance of class <i>\Vendor\Form\Form</i>.
<pre>
// new instance of form tag
$html = new \Vendor\Form\Form();
</pre>
Then must be defined form method (POST, GET)
<pre>
// set method
$form->setMethod('POST');
</pre>
and form action
<pre>
// set action
$form->setAction('index.php');
</pre>
Optionale parameter is inline form. It enables select between two visual forms (<i>inline form true</i> - more columns in one row and <i>inline form false</i> - one column for every row)
<pre>
// set display form - more columns in one row
$form->setInline(true);
</pre>
Create element <i>text</i> can be done  
<pre>
// input text field
$form->input()
     ->text('name', 'Name')
     ->html5Attrs('required')
     ->create();
</pre>
element <i>password</i>
<pre>     
// input password field
$form->input()
     ->password('password', 'Pasword')
     ->html5Attrs('required')
     ->create();
</pre>
element <i>checkbox</i>
<pre>
// input checkbox field
$form->input()
     ->checkbox('login', 'Remember', 'Remmember')
     ->create();
</pre>
where arguments are defined in order <i>name</i>, <i>label</i>, <i>value</i>, <i>id</i> and <i>maxlength</i>. Finally html code is got by calling public method <i>getCode()</i>
<pre>
// get created html code     
$form->getCode();
</pre>
</p>
