<?php

namespace App\Controllers;

use Core\Response;
use Core\Request;

require_once('Todo.php');

use \App\Models\Todo;

class TodosController {

	public function __construct()
	{

	}

	public function index($req)
	{
		$todo = new Todo();

		$todos = $todo->find();

		Response::json_array($todos);
	}

	public function view($id)
	{
		$todo = new Todo($id);

		Response::json($todo);
	}

	public function create($req)
	{
		$todo = new Todo();

		$todo->todo = $req->input('todo');
		$todo->user_id = $req->input('user_id');
		$todo->status = $req->input('status');

		$result = $todo->create();

		Response::json($result);
	}

	public function update($id, $req)
	{
		$todo = new Todo($id);

		$todo->todo = $req->inputOrDefault('todo', $todo->todo);
		$todo->user_id = $req->inputOrDefault('user_id', $todo->user_id);
		$todo->status = $req->inputOrDefault('status', $todo->status);

		$result = $todo->update();

		Response::json($result);
	}

	// Delete and return deleted object
	public function delete($id, $req)
	{
		$todo = new Todo();

		if($todo->findById($id))
		{
			$todo->delete();			

			Response::json($todo);
		}
		else {
			Response::error(Response::NOT_FOUND_ERROR_CODE, 'todo not found');
		}
	}

}