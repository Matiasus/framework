<h1>Framework</h1>
     <p>Simple PHP framework with Dependency Injection</p>
<h2>Html</h2>
     <p>Instance of class creates html tag. Class doesn't check proper attributes for given html tag. It only recognizes self         closing tags.</p>
<h3>Examples</h3>
<h4>Example 1</h4>
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
</pre>
</p>
outputs
<p>
<pre>
&lt;div id='id-div'&gt;This is my first div container!&lt;/div&gt;
</pre>
</p>
<h4>Example 2</h4>
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
outputs
<p>
<pre>
&lt;input type='text' \&gt;
</pre>
</p>
<h4>Form</h4>
<p>
Simple html form creator allows to create custimised form html element. Starts with creating a new Instance of class <i>\Vendor\Form\Form</i>.
<pre>
// new instance of form tag
$html = new \Vendor\Form\Form();
</pre>
Then have to be set sending method (POST, GET) by
<pre>
// set method
$form->setMethod('POST');
</pre>
and acction (where can be send form request) by:
<pre>
// set action
$form->setAction('index.php');
</pre>
It enables select between two visual forms (<i>inline form true</i> - more columns in one row and <i>inline form false</i> - one column for every row)
<pre>
// set display form - more columns in one row
$form->setInline(true);
</pre>
Creating required element is for given examples <i>text</i>
<pre>
// input text field
$form->input()
     ->text('name', 'Name')
     ->html5Attrs('required')
     ->create();
</pre>
or <i>password</i>
<pre>     
// input password field
$form->input()
     ->password('password', 'Pasword')
     ->html5Attrs('required')
     ->create();
</pre>
or <i>checkbox</i>
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
