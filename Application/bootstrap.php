<?php

// global container
$global = null;

// Path to autoloader
$autoloader_file = dirname(dirname(__FILE__)).'/Vendor/Autoloader/autoloader.class.php';
// Path to config file
$config_ini_file = dirname(dirname(__FILE__)).'/Application/Config/config.php.ini';

try {
  // autolader
  if (!file_exists($autoloader_file) || 
      !is_readable($autoloader_file)) { 
    // vynimka
    throw new Exception('Bootstrap [Line: '.__LINE__.']: File <b>'.$autoloader_file.'</b> not exists or can\'t load!');
  }
  // Call autoloader
  require_once($autoloader_file);
  // Autoloader
  // @param Void
  // @return Instance \Vendor\Autoloader\Autoloader 
  $autoload = new \Vendor\Autoloader\Autoloader();

  // Reflection
  // @param void
  // @return Instance of \Vendor\Reflection\Reflection
  $reflection = new \Vendor\Reflection\Reflection();

  // DI Container
  // @param \Vendor\Reflection\Reflection
  // @return Instance of \Vendor\Di\Container
  $di_container = new \Vendor\Di\Container($reflection);

  // Config
  // @param String - path to config file
  // @return Instance \Vendor\Config\Config
  $di_container->store('\Vendor\Config\File', $config_ini_file);
  // parse INI file
  $di_container->service('\Vendor\Config\File')->parse();

  // Route
  // @param void
  // @return Instance of \Vendor\Route\Route
  $di_container->store('\Vendor\Route\Route');
  // explode url address
  $di_container->service('\Vendor\Route\Route')->explodeUrl();
  // build namespace of controller
  $di_container->service('\Vendor\Route\Route')->initValueUrl();

  // Datum
  // @param void
  // @return Instance of \Vendor\Date\Date
  $di_container->store('\Vendor\Date\Date');

  // Buffer
  // @param void
  // @return Instance of \Vendor\Buffer\BUffer
  $di_container->store('\Vendor\Buffer\Buffer');

  // Cookie
  // @param void
  // @param \Vendor\Datum\Datum
  // @return Instance of \Vendor\Cookie\Cookie
  $di_container->store('\Vendor\Cookie\Cookie');

  // Session
  // @param void
  // @return Instance of \Vendor\Session\Session
  $di_container->store('\Vendor\Session\Session');
  // @fun Launch session
	$di_container->service('\Vendor\Session\Session')->launchSession();

  // Mysql
  // @param void
  // @return Instance of \Vendor\Connection\Mysql
  $di_container->store('\Vendor\Connection\Mysql');
  // mYSql connection
  $di_container->service('\Vendor\Connection\Mysql')->connect(
    'mysql'
    , \Vendor\Config\File::get('MYSQL', 'HOST')
    , \Vendor\Config\File::get('MYSQL', 'DTBS')
    , \Vendor\Config\File::get('MYSQL', 'NAME')
    , \Vendor\Config\File::get('MYSQL', 'PASS')
    , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
  );

  // Database
  // @param \Vendor\Connection\Mysql
  // @return Instance of \Vendor\Database\Database
  $di_container->store('\Vendor\Database\Database');

  // Database
  // @param \Vendor\Database\Database
  // @return Instance of \Vendor\User\User
  $di_container->store('\Vendor\User\User');

  // Generator
  // @param \Vendor\User\User
  // @return Instance of \Vendor\Generator\Generator
  $di_container->store('\Vendor\Generator\Generator');

  // Controller
  // @param \Vendor\Controller\Creator
  // @return Void
  $creator = new \Vendor\Controller\Creator($di_container);
  // create controller
  $creator->controller();

  //print_r($di_container);

  // Template
  // @param \Vendor\Container\Container
  // @param \Vendor\Controller\Controller
  // @return Void
  $view = new \Vendor\Template\Template($di_container);
  // Render processed page
  $view->render();
/*

*/
}
// -------------------------------------------------------------------------------------+
//                                  ERRORS DISPLAY                                      |  
catch (\Exception $exception) {
  // check errors and 
  // check if object errors exists
  if (null !== $global &&
      $global->objectExists('errors'))
  {
    // set title of error page
    $global->service('errors')
           ->set('title', 'ERROR');
    // set body with error message
    $global->service('errors')
           ->set('body', $exception->getMessage(), "<br/>Error at line: ", $exception->getLine());
    // render error page
    $global->service('errors')
           ->render();
  // if global container with errors does't exists
  } else {
    // show error messages
    echo "<html>\n";
    echo "  <head>\n";
    echo "  <title>ERROR</title>\n";
    echo " <link rel=\"icon\" href=\"images/favicon.ico\" />\n";
    echo " <link rel=\"stylesheet\" style=\"text/css\" href=\"/Public/css/error.css\" />\n";
    echo "  </head>\n";
    echo "  <body>\n";
    echo "    <div class=\"main\">\n";
    echo "      <div class=\"content\">\n";
    echo "        <h1>ERROR</h1>\n";
    echo "           ". $exception->getMessage() . "\n";
    echo "        </div>\n"; 
    echo "    </div>\n";
    echo "  </body>\n";
    echo "</html>";
  }
}
// Session destroy
\Vendor\Session\Session::destroy('', true);
