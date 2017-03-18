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

    // Config
    // @param String - path to config file
    // @return Instance \Vendor\Config\Config
    $config = new \Vendor\Config\File($config_ini_file);
    // parse INI file
    $config->parse();

    // Route
    // @param void
    // @return Instance of \Vendor\Route\Route
    $route = new \Vendor\Route\Route();
    // explode url address
    $route->explodedUrl();

    // Datum
    // @param void
    // @return Instance of \Vendor\Datum\Datum
    $datum = new \Vendor\Datum\Datum();

    // Cookie
    // @param void
    // @param \Vendor\Datum\Datum
    // @return Instance of \Vendor\Cookie\Cookie
    $cookie = new \Vendor\Cookie\Cookie();

    // Session
    // @param void
    // @return Instance of \Vendor\Session\Session
    $session = new \Vendor\Session\Session();
    // @fun Launch session
		$session->launchSession();

 /*      
  
    
    // Mysql
    // @param \Vendor\Errors\Errors
    $mysql = new \Vendor\Mysql\Mysql($global->get('errors')));
    
    // Sql connection
    $global->get('mysql')
           ->connect(
              'connection'
              , $global->getConfig()['Mysql']['Host']
              , $global->getConfig()['Mysql']['Database']
              , $global->getConfig()['Mysql']['Name']
              , $global->getConfig()['Mysql']['Password']
              , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
    
    // Database
    // @param \Vendor\Mysql\Mysql
    // @param \Vendor\Database\Database
    // @return Void
    $database = new \Vendor\Database\Database($mysql);
    // User
    // @param \Vendor\Session\Session 
    // @param new \Vendor\User\User
    // @return Void
    $global->set('user', new \Vendor\User\User($global->get('session'),
                                               $global->get('database'),
                                               $global->getConfig()));
    //
    //                          END OF GLOBAL OBJECTS CONTAINER                           |
    // -----------------------------------------------------------------------------------+
    //                                   MVC CONTAINER                                    |
    // Controller
    // @param \Vendor\Container\Container
    // @return Void
    $controller = new \Vendor\Controller\Controller($global);

    // Template
    // @param \Vendor\Container\Container
    // @param \Vendor\Controller\Controller
    // @return Void
    $mvc->set('view', new \Vendor\Template\Template($global, 
                                                    $mvc->get('controller')));
    //
    //                               END OF MVC CONTAINER                                 |
    // -----------------------------------------------------------------------------------+

    // Render processed page
    $mvc->get('view')
        ->render();
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
      $global->get('errors')
             ->set('title', 'ERROR');
      // set body with error message
      $global->get('errors')
             ->set('body', $exception->getMessage(), "<br/>Error at line: ", $exception->getLine());
      // render error page
      $global->get('errors')
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
    // ukoncenie programu
    exit(0);
}
