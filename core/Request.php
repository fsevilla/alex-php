<?php

namespace Core;

use Core\Routing\Routes;

class Request {

  public static function handle($uri)
  {

  	$method = strtolower($_SERVER['REQUEST_METHOD']);

  	$route = Routes::find($method, $uri);

  	if(!$route)
  	{
  		Response::error(404, "page not found");
  	} else
  	{
  		try {
      		Autoload::loadControllerAndExecute($route->action);  		
  	  	} catch(Exception $e) {
  	  		throw new Exception("Failed to load the controller".$e->getMessage());
  	  	}
  	}

  }

  public static function getCurrentRequest()
  {
    $req = new Request();

    $req->params = Routes::getCurrentRouteParams();
    $req->headers = getallheaders();


  	return $req;
  }

  public function getRouteParams()
  {
    return Routes::getCurrentRouteParams();
  }

  public function method()
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  private function getRequestData()
  {
    $method = $this->method();
    $params;
    
    switch ($method) {
      case 'get':
        $params = $_GET;
        break;
      case 'post':
        $params = $_POST;
        break;
      case 'put':
        parse_str(file_get_contents("php://input"),$params);
        break;
      case 'delete':
        parse_str(file_get_contents("php://input"),$params);
        break;
      default:
        $params = $_REQUEST;
        break;
    }

    return $params;
  }

  public function input($field)
  {
    $params = $this->getRequestData();
    $value = $params[$field] ? $params[$field] : $_REQUEST[$field];
    
    return addslashes(strip_tags(trim($value)));
  }

  public function inputOrDefault($field, $default)
  {
    $params = $this->getRequestData();
    $value = $params[$field] ? $params[$field] : $_REQUEST[$field];

    return $value ? $this->sanitize($value) : $default;
  }

  public function sanitize($data)
  {
    return addslashes(strip_tags(trim($data)));
  }

}