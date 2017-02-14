<?php
	// Copyright (C) 2017  captaincode
	
	// This program is free software: you can redistribute it and/or modify it
	// under the terms of the GNU General Public License as published by the Free
	// Software Foundation, either version 3 of the License, or (at your option)
	// any later version.
	
	// This program is distributed in the hope that it will be useful, but WITHOUT
	// ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
	// FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
	// more details.
	
	// You should have received a copy of the GNU General Public License along
	// with this program.  If not, see <http://www.gnu.org/licenses/>.
	
	namespace MyApp\Kernel\Entity;

	use MyApp\Kernel\Entity\IEntity;
	use Symfony\Component\Validator\Mapping\ClassMetadata;
	use Symfony\Component\Validator\Constraints as Assert;

	class User implements IEntity{
		private $id;
		private $email;
		private $pass;

		public function __construct($id=0, $email="", $pass=""){
			$this->id = $id;
			$this->email = $email;
			$this->pass = $pass;
		}

		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getEmail(){
			return $this->email;
		}

		public function setEmail($email){
			$this->email = $email;
		}

		public function getPass(){
			return $this->pass;
		}

		public function setPass($pass){
			$this->pass = $pass;
		}

		/**
		 * @Override
		 */
		public function toArray(){
			return [
				"id" => $this->id,
				"email" => $this->email,
				"pass" => $this->pass
			];
		}

		//validator method
		public static function loadValidatorMetadata(ClassMetadata $metadata){
			$metadata->addPropertyConstraint("id", new Assert\Regex([
				"pattern" => "/^\d+$/",
				"message" => "The user id needs to be a number"
			]));

			$metadata->addPropertyConstraint("email", new Assert\Email([
				"message" => "The user email needs to be valid"
			]));

			$metadata->addPropertyConstraint("pass", new Assert\Regex([
				"pattern" => "/^[a-zA-Z_0-9]{6,}$/",
				"message" => "The password needs to contain at least six numbers or lower characters or upper characters or a mix between them"
			]));
		}
	}