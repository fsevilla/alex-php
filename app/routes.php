<?php

use Core\Routing\Routes;

Routes::get('', 'Homepage/HomePageController::view');

// Routes::get('todos', 'Todos/TodosController::index');
// Routes::get('todos/:id', 'Todos/TodosController::view');
// Routes::post('todos', 'Todos/TodosController::create');
// Routes::put('todos/:id', 'Todos/TodosController::update');
// Routes::delete('todos/:id', 'Todos/TodosController::delete');

Routes::crud('todos', 'Todos/TodosController');
/*
 TODO: implement CRUD;
	Eg:

	Routes::CRUD('lorem', 'TodosController')

	It will create the following routes:
	
	get('todos/')
	get('todos/:id')
	post('todos/')
	put('todos/:id')
	delete('todos/:id');


	Controller must have the following methods defined:

	class TodosController {
	
		index() {}

		view() {} 

		create() {}

		update() {}

		delete() {}

	}
 */