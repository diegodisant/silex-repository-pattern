<?php
	namespace MyApp\Kernel\HttpComponent\Controller;

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use MyApp\Kernel\Entity\User;
	use MyApp\Kernel\Exception\UserDuplicateEmailException;

	class UserControllers implements ControllerProviderInterface{
		/**
		 * @Override
		 */
		public function connect(Application $app){
			$controllers = $app["controllers_factory"];

			$controllers->get("/users.json", function() use($app){
				$users = $app["repository.users"]->getAll();

				if($users)
					return $app->json($users);
			
				//return a msg if there is not users in the database
				return new JsonResponse(["msg" => "There is not users in the database"], 404);
			});

			$controllers->get("/user.json/{id}", function($id) use($app){
				$user = $app["repository.users"]->getById($id);

				if($user)
					return $app->json($user);

				return new JsonResponse(["msg" => "There user id requested [$id] doesn't exists in the database"]);
			})
				->assert("^\d+$", "id");

			$controllers->post("/user.add", function(Request $request) use($app){
				try{
					$user_object = new User();
					$user_object->setEmail($request->get("email"));
					$user_object->setPass($request->get("pass"));

					$user_inserted = $app["repository.users"]->add($user_object);

					if($user_inserted)
						return new JsonResponse(["msg" => "The user was inserted correctly in database"], 201);
					else
						throw new UserDuplicateEmailException("", $user_object->getEmail());
				}
				catch(UserDuplicateEmailException $ex){
					return new JsonResponse(["msg" => $ex->getMessage()], 400);
				}
			})
				->before(function(Request $request, Application $app){
					$email = $request->get("email");
					$pass = $request->get("pass");

					$user_object = new User();
					$user_object->setEmail($email);
					$user_object->setPass($pass);

					$errors = $app["validator"]->validate($user_object);

					//check if exists errors in the validation
					if(count($errors) > 0){
						$msg = "";

						//concatenate the errors
						foreach($errors as $error)
							$msg .= $error->getMessage()."\n";

						return new JsonResponse(["msg" => $msg], 400);
					}
				});

			$controllers->post("/user.edit", function(Request $request) use($app){
				$user_object = new User($request->get("id"), $request->get("email"), $request->get("pass"));

				$user_updated = $app["repository.users"]->edit($user_object);

				if($user_updated)
					return new JsonResponse(["msg" => "The user was updated correctly in database"], 201);

				return new JsonResponse(["msg" => "The usar was not updated correctly in database"], 400);
			})
				->before(function(Request $request, Application $app){
					$id = $request->get("id");
					$email = $request->get("email");
					$pass = $request->get("pass");

					$user_object = new User($id, $email, $pass);

					$errors = $app["validator"]->validate($user_object);

					if(count($errors) > 0){
						$msg = "";

						foreach($errors as $error)
							$msg .= $error->getMessage()."\n";

						return new JsonResponse(["msg" => $msg], 400);
					}
				});

			$controllers->post("/user.del", function(Request $request) use($app){
				$user_id = $request->get("id");

				//executes the delete operation
				$user_deleted = $app["repository.users"]->del($user_id);

				if($user_deleted)
					return new JsonResponse(["msg" => "The user was deleted correctly"], 202);

				return new JsonResponse(["msg" => "The user was not deleted correctly"], 404);
			})
				->before(function(Request $request, Application $app){
					$user_id = $request->get("id");

					//check thew 
					if(!preg_match("/^\d+$/", $user_id))
						return new JsonResponse(["msg" => "The requested user [$user_id] doesn't exists on the database"], 404);
				});

			$controllers->after(function(Request $request, Response $response) use($app){
				$response->headers->set("content-type", "application/json");
			});

			return $controllers;
		}
	}