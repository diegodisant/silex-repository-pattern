<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	//load the Symfony and Silex Framework
	require __dir__."/../vendor/autoload.php";
	
	//load application components
	require __dir__."/../src/kernel/exception/ExceptionLoader.php";
	require __dir__."/../src/kernel/entity/EntityLoader.php";
	require __dir__."/../src/kernel/repository/RepositoryLoader.php";
	require __dir__."/../src/kernel/httpcontroller/ControllerLoader.php";	

	//build and return the current application
	$app = require __dir__."/../src/app.php";

	//mount the application components
	require __dir__."/../src/mount-http-controllers.php";

	$app->run();