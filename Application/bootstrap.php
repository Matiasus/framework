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
    $reflection->bind('\Vendor\Connection\Iconnection', function () use ($arguments) { 
      return new \Vendor\Connection\Mysql($arguments); 
    });
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

