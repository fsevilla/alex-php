<?php

/*
 Include the Application maker
*/

require_once __DIR__.'/Application.php';


/*
 Load Global methods for the Application to use
*/
 
require_once __DIR__.'/helpers/GlobalHelpers.php';

require_once __DIR__.'/Response.php';

require_once __DIR__.'/Request.php';

require_once __DIR__.'/Autoload.php';


/*
 Load Routing Component
*/

require_once __DIR__.'/routing/RoutesHandler.php';

require_once __DIR__.'/routing/Routes.php';

/*
 Load Simple Logger Component
*/

require_once __DIR__.'/logger/Log.php';


/*
 Load Model and Database Handler
*/

require_once __DIR__.'/database/Database.php';

require_once __DIR__.'/Model.php';

require_once __DIR__.'/../db/drivers/driver.php';


/*
 Include the Application Routes
*/

 require_once __DIR__.'/../app/routes.php';



