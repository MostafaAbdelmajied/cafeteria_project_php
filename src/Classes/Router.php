<?php

namespace Src\Classes;

use Src\Exceptions\RouteNotFoundException;

class Router
{
    private static $routes = [];
    private static $middleware = null;

    public static function register($method, $url, $action)
    {
        if (!isset(static::$routes[$method][$url])) {

            static::$routes[$method][$url] = [
                "action" => $action, 
                ];
            if(! empty(static::$middleware)){
                static::$routes[$method][$url]['middleware'] = static::$middleware;
            }
        }
    }

    public static function get($url, $action)
    {
        static::register('GET', $url, $action);
    }

    public static function post($url, $action)
    {
        static::register('POST', $url, $action);
    }

    public static function group($options, $callback)
    {
        if (isset($options['middleware'])) {
            static::$middleware = $options['middleware'];
        }

        $callback();

        static::$middleware = null;
    }

    public static function resolve()
    {
        
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath != "/") {
            $url = str_replace($basePath, '', $url);
        }

        $url = '/' . trim($url, '/');

        if (isset(static::$routes[$method][$url])) {
            $route = static::$routes[$method][$url];
            $action = $route['action'];
            $middleware = isset($route['middleware']) ? $route['middleware'] : null;

            if($middleware) 
                static::handleMiddleware($middleware);

            [$class, $method] = $action;
            if (class_exists($class) && method_exists($class, $method)) {
                $class = new $class();
                return call_user_func_array([$class, $method], []);
            }

        }else{
            throw new RouteNotFoundException();
        }
    }


    public static function handleMiddleware($middleware)
    {
        if(is_array($middleware)){
            foreach ($middleware as $m) {
                static::runMiddleware($m);
            }
        }else{
            static::runMiddleware($middleware);
        }
    }

    private static function runMiddleware($middleware)
    {
        $parts = explode(":", $middleware);
        $class = $parts[0];
        $param = $parts[1] ?? null;

        $instance = new $class();

        $param ? $instance->handle($param) : $instance->handle();
    }
}