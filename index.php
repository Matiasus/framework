<?php

	// Error reporting ON
	ini_set('error_reporting', E_ALL | E_NOTICE);
	ini_set('display_errors', 'on');
  ini_set('short_open_tag', 'on');
	
	// Set time zone
	date_default_timezone_set('Europe/Bratislava');

  // Call Bootstrapper
	require_once (dirname(__FILE__) . "/Application/bootstrap.php");
