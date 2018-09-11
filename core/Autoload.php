<?php

namespace Core;

class Autoload {

	
	private static $componentsFolder = 'Components';

	private static $controllersNamespace = 'Controllers';


	public static function loadController($classPath)
	{

		include_once(__DIR__.'/../app/'.self::$componentsFolder.'/'.$classPath.'.php');

		$className = self::getClassName($classPath);

		$class = self::getControllerNameSpace().$className;

		return new $class();

	}

	private static function getControllerNameSpace()
	{
		return '\\App\\'.self::$controllersNamespace.'\\';
	}

	private static function getClassName($classPath)
	{
		$controllerAndMethod = explode('::', $classPath);
		$classNameArr = explode('/', $controllerAndMethod[0]);
		$className = end($classNameArr);

		return $className;
	}

	public static function loadControllerAndExecute($class)
	{
		try {
			$controllerMethod = explode('::', $class);

			$controller = self::loadController($controllerMethod[0]);

			$method = $controllerMethod[1];

			if(method_exists($controller, $method)) {
				$req = Request::getCurrentRequest();
				// $params = $req->params;
				$params = [];
				foreach ($req->params as $key => $param) {
					$params[] = $param;
				}
				// Push $req data to be the last parameter sent
				// to the controller's method
				$params[] = $req;
				$className = self::getClassName($class);
				$methodString = self::getControllerNameSpace().$className.'::'.$method;

				return call_user_func_array($methodString, $params);

			} else {
				Response::error(422, "Method is not defined in Controller ".$controllerMethod[0]);
			}
		} catch (Exception $e ) {
			Response::error(422, "Failed to load controller".$e->getMessage());
		}


	}

}