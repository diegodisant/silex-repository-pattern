<?php
	
	namespace MyApp\Kernel\Repository;

	use Silex\Application;
	use MyApp\Kernel\Entity\User;
	use MyApp\Kernel\Entity\IEntity;
	use MyApp\Kernel\Repository\Repository;
	use MyApp\Kernel\Repository\IRepository;
	use MyApp\Kernel\Repository\ICrudRepository;

	class UserRepository extends Repository implements IRepository, ICrudRepository{
		public function __construct(Application $app){
			parent::__construct($app);
		}

		public function add(IEntity $entity){
			//check if the user exists, and if exists return an error
			$user_exists = $this->app["db"]->fetchAssoc("select id from users where email=? limit 1", [$entity->getEmail()]);

			//if the user not exists insert the user in the database
			if(!$user_exists)
				return $this->app["db"]->insert("users", ["email" => $entity->getEmail(), "pass" => md5($entity->getPass())]);

			return false;
		}

		public function edit(IEntity $entity){
			return $this->app["db"]->update("users", ["email" => $entity->getEmail(), "pass" => md5($entity->getPass())], ["id" => $entity->getId()]);
		}

		public function del($entity_id){
			return $this->app["db"]->delete("users", ["id" => $entity_id]);
		}

		public function getById($entity_id){
			return $this->app["db"]->fetchAssoc("select * from users where id=? limit 1", [(int) $entity_id]);
		}

		public function getAll(){
			return $this->app["db"]->fetchAll("select * from users");
		}
	}