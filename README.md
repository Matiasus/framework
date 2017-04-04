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
Simple html form creator
<pre>
// new instance of form tag
$html = new \Vendor\Form\Form();
// set method
$form->setMethod('POST');
// set action
$form->setAction('index.php');
// set display form
$form->setInline(true);
// input text field
$form->input()
     ->text('Username', 'Meno/Name')
     ->html5Attrs('required')
     ->create();
// input password field
$form->input()
     ->password('Passwordname', 'Heslo/Pasword')
     ->html5Attrs('required')
     ->create();
// input text field
$form->input()
     ->checkbox('Persistentlog', 'Pamätaj prihlásenie', 'Pamataj')
     ->create();
// get created html code     
$form->getCode();
</pre>
</p>
