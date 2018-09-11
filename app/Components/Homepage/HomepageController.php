<?php

namespace App\Controllers;

use Core\Response;
use Core\Request;

require_once('Homepage.php');

use \App\Models\Homepage;

class HomePageController {

	public function __construct()
	{

	}

	public function view($req)
	{
		echo "<h1>Welcome to ALEx</h1>";
        echo "<h2>The beginning of it all!</h2>";
        echo "<p>Version: ".APP_VERSION."</p>";
	}

}