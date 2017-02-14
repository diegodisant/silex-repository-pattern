<?php

	use Silex\Application;
	use Silex\Provider\ValidatorServiceProvider;
	use Silex\Provider\DoctrineServiceProvider;
	use MyApp\Kernel\Repository\UserRepository;

	$app = new Application();
	
	$app["debug"] = true;
	$app->register(new ValidatorServiceProvider());
	$app->register(new DoctrineServiceProvider(), [
		"db.options" => [
			"driver" => "pdo_mysql",
			"dbname" => "silex_test_users",
			"host" => "localhost",
			"user" => "root",
			"password" => "data.set",
			"charset" => "utf8"
		]
	]);

	//build application repositories in services
	$app["repository.users"] = $app->share(function() use($app){
		return new UserRepository($app);
	});

	return $app;