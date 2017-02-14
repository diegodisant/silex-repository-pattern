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

		}

		public function edit(IEntity $entity){

		}

		public function del($entity_id){

		}

		public function getById($entity_id){

		}

		public function getAll(){
			
		}
	}