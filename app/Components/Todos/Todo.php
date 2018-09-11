<?php

namespace App\Models;

use Core\Model;

class Todo extends Model {

	// Database table. If name is the same as the classname, it can be omitted 
	protected $table = 'todos';

	protected $fields = [
		'todo',
		'status',
		'user_id',
	];

}