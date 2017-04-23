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

    // Parser
    // @param String - path to config file
    // @return Instance \Vendor\Config\Parser
    $parser = new \Vendor\Config\Parser($config_ini_file);

    // Config
    // @param \Vendor\Config\Parser
    // @return Instance of \Vendor\Config\File
    $config = new \Vendor\Config\File($parser);

    // Route
    // @param void
    // @return Instance of \Vendor\Route\Route
    $route = new \Vendor\Route\Route();

    // Datum
    // @param void
    // @return Instance of \Vendor\Date\Date
    $date = new \Vendor\Date\Date();

    // Cookie
    // @param void
    // @param \Vendor\Datum\Datum
    // @return Instance of \Vendor\Cookie\Cookie
    $cookie = new \Vendor\Cookie\Cookie();

    // Session
    // @param void
    // @return Instance of \Vendor\Session\Session
    $session = new \Vendor\Session\Session();

    // Mysql arguments 
    // @param - define host
    // @param - define database
    // @param - define name
    // @param - define password
    $arguments = array(
        \Vendor\Config\File::getArray('ICONNECTION')['MYSQL']['HOST']
      , \Vendor\Config\File::getArray('ICONNECTION')['MYSQL']['DTBS']
      , \Vendor\Config\File::getArray('ICONNECTION')['MYSQL']['NAME']
      , \Vendor\Config\File::getArray('ICONNECTION')['MYSQL']['PASS']
      , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    // set arguments before creating interface class
    $reflection->bind('\Vendor\Connection\Iconnection', function () use ($arguments) { return new \Vendor\Connection\Mysql($arguments); });
    // Create controller
    $reflection->service(\Vendor\Route\Route::get('controller_namespace'));

    // called controller
    $controller = $reflection->get(Vendor\Route\Route::get('controller_namespace'));
    // called method
    $method = $controller->callMethod();
    // render method
    $controller->$method();

    // set arguments before creating interface class
    $reflection->bind('\Vendor\Controller\Icontroller', function () use ($controller) { return $controller; });
    // Template
    // @return Instance of \Vendor\Template\Template
    $reflection->service('\Vendor\Template\Template');
    // Render processed page
    $reflection->get('\Vendor\Template\Template')->render();


/*
    // Generator
    // @param \Vendor\User\User
    // @return Instance of \Vendor\Generator\Generator
    $generator = new \Vendor\Generator\Generator($user);

    // Controller
    // @param \Vendor\Di\Container
    // @return Instance of \Vendor\Controller\Creator
    $controller = new \Vendor\Controller\Creator($route, $reflection);


exit;
/*
    // Mysql
    // @param void
    // @return Instance of \Vendor\Connection\Mysql
    $mysql = new \Vendor\Connection\Mysql($arguments);

    // Mysql
    // @param void
    // @return Instance of \Vendor\Connection\Connection
    $connection = new \Vendor\Connection\Connection($mysql);

    // mYSql connection
    $mysql->connect(
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
    $database = new \Vendor\Database\Database($mysql);

    // User
    // @param \Vendor\Database\Database
    // @return Instance of \Vendor\User\User
    $user = new \Vendor\User\User($database);

    // Generator
    // @param \Vendor\User\User
    // @return Instance of \Vendor\Generator\Generator
    $generator = new \Vendor\Generator\Generator($user);

    // Controller
    // @param \Vendor\Di\Container
    // @return Instance of \Vendor\Controller\Creator
    $controller = new \Vendor\Controller\Creator($route, $reflection);
/*

    // DI Container
    // @param \Vendor\Reflection\Reflection
    // @return Instance of \Vendor\Di\Container
    $di_container = new \Vendor\Di\Container($reflection);

    // Parser
    // @param String - path to config file
    // @return Instance \Vendor\Config\Parser
    //$parser = new \Vendor\Config\Parser($config_ini_file);
    // Config
    // @param @var \Vendor\Config\Parser
    // @return Instance \Vendor\Config\Config
    //$config = new \Vendor\Config\File($parser);
    $di_container->store('\Vendor\Config\File', $config_ini_file);

    // Route
    // @param void
    // @return Instance of \Vendor\Route\Route
    $di_container->store('\Vendor\Route\Route');

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

    // User
    // @param \Vendor\Database\Database
    // @return Instance of \Vendor\User\User
    $di_container->store('\Vendor\User\User');

    // Generator
    // @param \Vendor\User\User
    // @return Instance of \Vendor\Generator\Generator
    $di_container->store('\Vendor\Generator\Generator');

    // Controller
    // @param \Vendor\Di\Container
    // @return Instance of \Vendor\Controller\Creator
    $controller = new \Vendor\Controller\Creator($di_container->service(''));

print_r($di_container);

/*
    // create controller according to controller in url address
    // and store it into DI container
    $controller->create($di_container->service('\Vendor\Route\Route')->get('controller_namespace'));
    // call render method according to view in url address
    $controller->callMethod($di_container->service('\Vendor\Route\Route')->get('view'));

    // Template
    // @param \Vendor\Di\Container
    // @return Instance of \Vendor\Template\Template
    $view = new \Vendor\Template\Template($di_container);
    // Render processed page
    $view->render();
*/
  }
  // -------------------------------------------------------------------------------------+
  //                                  ERRORS DISPLAY                                      |  
  catch (\Exception $exception) {
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
  // Session destroy
  \Vendor\Session\Session::destroy('', true);

