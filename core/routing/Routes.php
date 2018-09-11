<?php

namespace Core\Routing;

class Routes {
  
  private static $group = '';

  private static function buildUri($uri)
  {
    return self::$group ? self::$group.'/'.$uri : $uri;
  }
  
  public static function get($uri, $action)
  {
    $uri = self::buildUri($uri);
    RoutesHandler::getInstance()->setPath('get', $uri, $action);
  }

  public static function post($uri, $action)
  {
    $uri = self::buildUri($uri);
    RoutesHandler::getInstance()->setPath('post', $uri, $action);
  }

  public static function put($uri, $action)
  {
    $uri = self::buildUri($uri);
    RoutesHandler::getInstance()->setPath('put', $uri, $action);
  }

  public static function delete($uri, $action)
  {
    $uri = self::buildUri($uri);
    RoutesHandler::getInstance()->setPath('delete', $uri, $action);
  }

  public static function find($method, $uri)
  {
    $uri = self::buildUri($uri);
    return RoutesHandler::getInstance()->findPath($method, $uri);
  }

  public static function getCurrentRouteParams()
  {
    $currentRoute = RoutesHandler::getInstance()->getCurrentRoute();
    return $currentRoute->params;
  }

  public static function crud($uri, $controller)
  {
    RoutesHandler::getInstance()->setPath('get', $uri, $controller.'::index'); // List all
    RoutesHandler::getInstance()->setPath('get', $uri.'/:id', $controller.'::view'); // Get details
    RoutesHandler::getInstance()->setPath('post', $uri, $controller.'::create'); // Create record
    RoutesHandler::getInstance()->setPath('put', $uri.'/:id', $controller.'::update'); // Update record
    RoutesHandler::getInstance()->setPath('delete', $uri.'/:id', $controller.'::delete'); // Delete record
  }

  public static function group($group, $routesCallback)
  {
    self::$group = $group;
    $routesCallback();
    self::$group = '';
  }
}