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
	
	namespace MyApp\Kernel\Repository;

	use MyApp\Kernel\Entity\IEntity;

	interface ICrudRepository{
		/**
		 * [add creates a new entity in the database]
		 * @param IEntity $entity [current entity]
		 * @return bool 			[if the entity was added true]
		 */
		public function add(IEntity $entity);
		
		/**
		 * [edit updates an existent entity on the database]
		 * @param  IEntity $entity [current entity]
		 * @return bool            [if the entity was edited correctly true]
		 */
		public function edit(IEntity $entity);
		
		/**
		 * [del removes an existent entity on the database]
		 * @param  int $entity_id [current entity id]
		 * @return bool          [if the entity was deleted correctly true]
		 */
		public function del($entity_id);
		
		/**
		 * [getById retrieves one entity by id]
		 * @param  int $entity_id [the current entity id]
		 * @return array          [one array that represents the entity, null if there is not the requested entity in the database]
		 */
		public function getById($entity_id);

		/**
		 * [getAll retrieves all the data from database]
		 * @return array [one array that represents all the data, null if there is not entities in the database]
		 */
		public function getAll();
	}