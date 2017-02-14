<?php
	
	use MyApp\Kernel\HttpComponent\Controller\UserControllers;

	$app->mount("/private/user.panel",  new UserControllers());
	$app->get("/", function() use($app){
		return "<h1>Silex Repository Pattern Example</h1>";
	});