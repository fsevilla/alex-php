# ALEx PHP
(Application Lightweight Expeditor) A simple, lightweight and customizable RESTful application starter. 


## What is it?
ALEx is a simple, configurable quickstart project for building RESTful applications with PHP. 

## Why to come up with yet another Framework?
While it is intended to become a Framework, I wouldn't label it as such, just yet. Wait for it. 

ALEx is a set of libraries designed to rapidly build a RESTful application. What do you need? A database connection, an Apache server, create your models, controllers, define your routes and you are set. Simple, right?

ALEx is intented to assist on your API development by providing a solid and stable CORE which allows to to have your project up and running in less than 2 minutes. 

## Why is it better?
Better than what? There are bigger options which provide not only the scaffolding but also a set of command-line tools in order to build robust applications. We will get there, someday, but as of today, ALEx does one thing and one thing right: Start a project that can grow very darn easily. So if you DO compare this project with similar ones, I bet you will like it, enjoy it and most importantly, find a benefit from it (though enjoying it is the best part).

## Features
* App Configuration
* Dynamic routes Handler
* Request and Response methods
* Models to communicate with the Database
* Controllers to handle routes and actions

## Installation

Nah, not quite there, yet. I will get this via composer soon, but cloning it is all it takes for now. 

## Getting started
Still here? Well, lets talk about how to get this code, then. 
If you are here, I'd guess you are already familiar with GitHub. What are you waiting for, then? Just go ahead and clone the project:

```
git clone https://github.com/fsevilla/alex-php.git
```

### Configuring the Application
This project has 3 configuration files under the config folder


#### app.php
This file sets the general app's configuration, such as its name, version and error reporting level
```
return [
    'name' => 'ALEx',
    'app_prefix' => 'app',
    'version' => 'alpha',
    'env' => env('APP_ENV', 'local'),
    'error_reporting' => 'error', // none, parse, warning,  notice, error, all
    'url' => env('APP_URL', 'http://localhost'),
];
```

#### cors.php
In this file you will configure your headers for Cross-domain communication such as which methods are supported as well as the allowed headers and origins.
```
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Accept, Origin, Content-Type, Authorization, x-xsrf-token, x-csrf-token, Charset');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Credentials: true');
```

#### database.php
In case you need to connect your REST api with a database (which I suppose will be the case), simply set your credentials here:
```
return [
    'host' => 'localhost',
    'port' => '8889',
    'username' => '%db_username%',
    'password' => '%db_password%',
    'database' => '%db_name%',
    'driver' => 'mysqli', // mysql (default), mysqli
    'debug' => false // if true, it will print (echo) messages of the run queries and errors (if any)
];

```


## Getting into the Code
Ok, so we are here so you know how this project will help you build faster API's, right? right? 

It is as simple as this:
* Create a Table in your database with the fields you need
* Create a folder inside Components for the new module that you want to add. (Use Todos as an example)
* Create a model and a controller file (again, use Todos as an example)
* Define the routes for your new controller actions. 

### Model
The Model will allow you to read from and write to your database tables. This is the basic structure of your Model:

```
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

```

This class which extends from Model specifies which table to talk to, as well as the fields you want to save to (create and update queries).

Here is a list of properties you can configure in your Model:

|                    | Type            | Description                 | Default           |
| ------------------ | --------------- | --------------------------- | ----------------- |
| $table             | string          | name of table               |  className()      |
| $fields            | array           | fields use in create/update |  []               |
| $id_field          | string          | field use as identifier     |  id               |
| $select_fields     | string          | fields in SELECT query      |  *                |
| $update_timestamps | boolean         | set created_at & updated_at |  true             |



### Controller
A controller helps you define a set of actions to be taken based on the route and method the API is called by. 
E.g. if the API call is to http://myapi/todos via a GET method, you should define a GET route and a handler for it. 
This handler will be your controller, which is the action to be taken with the API is hit. 

```
<?php

namespace App\Controllers;

use Core\Response;


class TodosController {


    public function index($req)
    {
        Response::text("You hit the GET todos endpoint");
    }

}
```

### Routes
This quickstart project includes a Routes handler which allows you to define dynamic methods. 

#### Features
* You can define new routes in the app/routes.php without having to update the .htaccess file
* Supports route params which are automatically passed to your controller
* Includes a Request model to handle query params which are also passed to your controller

A route is a definition of the following:
* Request Method (GET, POST, PUT, DELETE)
* URI (/todos, /users, /settings, /account/profile)
* Query params (/users/:user_id)
* Controller
* Controller's Action

Defining routes is quite simple:

Default:
```
Routes::get('todos', 'Todos/TodosController::index');
Routes::get('todos/:id', 'Todos/TodosController::view');
Routes::post('todos', 'Todos/TodosController::create');
Routes::put('todos/:id', 'Todos/TodosController::update');
Routes::delete('todos/:id', 'Todos/TodosController::delete');
```

Groupping:
If you are defining a set of uris under the same level, you can group'em together. 
The routes above can also be set as follows:

```
Routes::group('todos', function() {
    Routes::get('', 'Todos/TodosController::index');
    Routes::get(':id', 'Todos/TodosController::view');
    Routes::post('', 'Todos/TodosController::create');
    Routes::put(':id', 'Todos/TodosController::update');
    Routes::delete(':id', 'Todos/TodosController::delete');
});
```

CRUD:
Most of the times, you will need to define the routes to get all the rows, a single row based on ID, create new, update existing or delete a row. Yes, a regular CRUD. For this, we created a shortcut that can be used like this:

```
Routes::crud('todos', 'Todos/TodosController');
```

Easier, right? Simply make sure you have defined the following methods on your Controller:
* index (GET all)
* view (GET details by ID)
* create (add new)
* update (update by ID)
* delete (delete by ID)


## New Features
So as mentioned, this is a work-in-progress project. Though I am confident you will already like it as is, and it will serve it's purpose. However, at the moment is meant for rapid API's more than robust web applications. We are getting there, bare with me just a few more days/weeks/months/years.. well, no, hopefully it won't take years. 

What's next? 
Some of the new stuff we are planning to include shortly:
* Support for middlewares
* Validations
* Query builder
* Template handling (at the moment it only responds with json's. Smarty is in the loop)
* Documentation website
* Composer (already mentioned this) to make it easier to install and upgrade
* API Builder (visual tool)

If you have any thoughts of how to make this not only cooler but even more helpful for you, BY ALL MEANS let me know!


## Contributing
This is still a green project. New, juts out of the box project. Contribution guidelines are not quite defined, yet. 
If, again, you have any great ideas for this want-to-become-a-framework project, create an issue, fork, drop an email, or simply hope for the best. I am really interested in hearing from you.


## Copyright & License
Copyright 2018 fsevilla. Code released under the [MIT license](https://github.com/fsevilla/alex-php/blob/master/LICENSE)