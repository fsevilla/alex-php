<?php

use Core\Routing\Routes;

Routes::get('', 'Homepage/HomePageController::view');

Routes::crud('todos', 'Todos/TodosController');

/*
 * The crud method above is a shortcut for the following:

	Routes::get('todos', 'Todos/TodosController::index');
	Routes::get('todos/:id', 'Todos/TodosController::view');
	Routes::post('todos', 'Todos/TodosController::create');
	Routes::put('todos/:id', 'Todos/TodosController::update');
	Routes::delete('todos/:id', 'Todos/TodosController::delete');

	// Methods index, view, create, update & delete should be available in the controller

	Another alternative to define routes is to group'em as follows:

	Routes::group('todos', function() {
	    Routes::get('', 'Todos/TodosController::index');
	    Routes::get(':id', 'Todos/TodosController::view');
	    Routes::post('', 'Todos/TodosController::create');
	    Routes::put(':id', 'Todos/TodosController::update');
	    Routes::delete(':id', 'Todos/TodosController::delete');
	});

	// The above prefixes todos/ to all the paths within the function 
*/

