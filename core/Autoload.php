<?php

namespace Core;

class Autoload {

	private static $componentsFolder = 'Components';

	private static $controllersNamespace = 'Controllers';

	private static $max_scan_depth = 3;

	public static function loadController($classPath)
	{
		$className = self::getClassName($classPath);

		$class = self::getControllerNameSpace().$className;

		if(!class_exists($class)) {
			include_once(__DIR__.'/../app/'.self::$componentsFolder.'/'.$classPath.'.php');
		}

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

	protected static function _require_all($dir, $depth=0) {
		// TODO: review the need of DIRECTORY_SEPARATOR

        if ($depth > self::$max_scan_depth) {
            return;
        }
        // require all php files
        $scan = glob("$dir/*");
        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once $path;
            }
            elseif (is_dir($path)) {
                self::_require_all($path, $depth+1);
            }
        }
    }

	public static function loadAllComponents() {
        $dir = __DIR__.'/../app/'.self::$componentsFolder;
        self::_require_all($dir, 0);
    }

}