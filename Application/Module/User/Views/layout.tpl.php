<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
  <head> 
    <title>{include title} | Sensum, non verba spectamus</title>  
    <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
    <meta name="description" content="Poznamkovy blog, Marián, Hrinko, Matiasus's" /> 
    <meta name="keywords" content="Poznamkovy blog, Marián, Hrinko, Matiasus's" /> 
    <link rel="stylesheet" type="text/css" href="/Public/css/style.css" />
    <link rel="shortcut icon" href="/Public/images/favicon.ico" />
    <script type="text/javascript" src="/Public/javascripts/script.js"></script>
  </head> 
  <body>
    <!-- Horna cast s nadpisom a logom -->
    <div id="bar">
      <div id="bar-inside">
        <h1 class="title">{include title}</h1>
        <span class="podtitul">&#8222;Sensum, non verba spectamus&#8220; - Dôležitý je zmysel, nie slová</span>
      </div>
    </div>
    <!-- Wrapper -->
    <div id="wrapper">
      <!-- Vypis flashovej hlasky -->
      <div id="flash">
        {include flashmessage}
      </div>
      <!-- Jadro stranky - obsah -->
      <div id="contentwrapper">
        {include content}
      </div>
    </div>
    <!-- Paticka stranky -->
    <div id="bar-foot">
      <div id="bar-foot-inside">
        <b style="color: #999;">Blog</b> &#169; 2014 by <b style="color: #777; font-family: 'Comic Sans MS', cursive, sans-serif;">Matiasus</b>
      </div>
      <endora>
    </div>
    <div id="bar-logo">
        <a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="21" width="60" /></a>
    </div>
  </body>
</html>
