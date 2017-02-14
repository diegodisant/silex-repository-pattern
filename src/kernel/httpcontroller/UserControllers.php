<?php
	namespace MyApp\Kernel\HttpComponent\Controller;

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;

	class UserControllers implements ControllerProviderInterface{
		/**
		 * @Override
		 */
		public function connect(Application $app){
			$controllers = $app["controllers_factory"];

			$controllers->get("/users.json", function(){

			});

			$controllers->after(function(Request $request, Response $response) use($app){
				$response->headers->set("content-type", "application/json");
			});

			return $controllers;
		}
	}