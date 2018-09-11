<?php

namespace Core\Routing;


final class RoutesHandler {

	private static $instance = null;

	private $routes;

	private $currentRoute;

	public function __construct()
	{
		$this->routes->get = [];
		$this->routes->put = [];
		$this->routes->post = [];
		$this->routes->delete = [];
	}

	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new RoutesHandler();
		}

		return self::$instance;
	}

	public function setPath($method, $uri, $action)
	{
		$path = $this->getPathSettings($uri);
		$path->action = $action;

		$method = strtolower($method);
		$this->routes->{$method}[] = $path;
	}

	public function findPath($method, $uri)
	{
		$_path = null;

		foreach ($this->routes->{$method} as $path) {
			if($this->testUri($uri, $path)) {
				$_path = $this->setCurrentRoute($path, $uri);
				break;
			}
		}

		return $_path;
	}

	private function testUri($uri, $path)
	{
		$uri = trim($uri,'/');

		if($uri === $path->uri)
		{
			return true;
		} else
		{
			// Will go through the scheme only if the 
			// number of parts of the url string match.
			// E.g. No need to test /home/:id with /home/:id/details 
			// as we know in advance they won't match
			if($this->deepTestUri($uri, $path))
			{
				return true;
			}

			return false;
		}
	}

	private function deepTestUri($uri, $path)
	{
		$uriSchemes = explode('/', $uri);
		if(count($uriSchemes) === count($path->_data))
		{
			$isMatch = true;
			foreach ($path->_data as $key => $scheme) {
				if($scheme->key !== $uriSchemes[$key] && $scheme->isParam === false) {
					$isMatch = false;
					break;
				}
			}

			return $isMatch;
		}

		return false;
	}

	private function setCurrentRoute($route, $uri)
	{
		$route->path = $uri;
		$route->params = $this->getParamsFromRoute($route);
		$this->currentRoute = $route;

		return $this->currentRoute;
	}

	private function getParamsFromRoute($route)
	{
		$uri = trim($route->path, '/');
		$uriSchemes = explode('/', $uri);
		$params = [];
		
		foreach ($route->_data as $key => $scheme) {
			if($scheme->isParam)
			{
				$params[$scheme->key] = $uriSchemes[$key];
			}
		}

		return $params;
	}

	private function getPathSettings($uri)
	{
		$path = (object)[];
		$path->uri = trim($uri);

		$scheme = explode('/', $uri);
		$_data = [];

		foreach ($scheme as $value) {
			$_path = (object)[];
			if(substr($value, 0, 1) === ':')
			{
				$_path->isParam = true;
				$_path->key = ltrim($value, ':');
			} else
			{
				$_path->isParam = false;
				$_path->key = $value;
			}

			if($value !== '')
			{
				$_data[] = $_path;				
			}
		}

		$path->_data = $_data;
		$path->params = [];

		return $path;
	}

	public function getCurrentRoute()
	{
		return $this->currentRoute;
	}

	private function getRelativePath()
	{
		$root = str_replace($_SERVER['DOCUMENT_ROOT'], "", __DIR__);

		$path = urldecode(
		    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
		);

		$uri = str_replace($root, "", $path);

		return $uri;
	}

}