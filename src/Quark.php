<?php

namespace Makiavelo\Quark;

use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

/**
 * Middlewares microframework
 */
class Quark
{
    public static $instance;
    public $routes = [];
    public $request;
    public $response;
    public $session;
    public $bag;

    protected function __construct()
    {
        $this->request = Request::get();
        $this->response = Response::get();
        $this->session = [];
        $this->bag = [];
    }

    /**
     * Get the current Quark instance.
     * 
     * @return Makiavelo\Quark\Quark
     */
    public static function app()
    {
        if (!self::$instance) {
            self::$instance = new Quark();
        }

        return self::$instance;
    }

    /**
     * Create a new instance and return it
     * This deletes the previous instance.
     * 
     * @return Makiavelo\Quark\Quark
     */
    public static function resetInstance()
    {
        self::$instance = new Quark();
        return self::$instance;
    }

    /**
     * Process all the middlewares based on a route.
     * 
     * @return void
     */
    public function start()
    {
        foreach ($this->routes as $route) {
            if ($route->match()) {
                $this->applyRoute($route);               
            }

            if (!$route->continue) {
                break;
            }
        }
    }

    /**
     * Execute a route's callback and update request params.
     * call_user_func requires a callable:
     * More info: https://www.php.net/manual/en/language.types.callable.php
     * 
     * @param Route $route
     * 
     * @return void
     */
    public function applyRoute(Route $route)
    {
        $this->request->addPathParams($route->params);
        call_user_func($route->callback, $this->request, $this->response);
    }

    /**
     * Add a routed middleware to the stack.
     * 
     * @param string $path
     * @param mixed $callback
     * @param string $method
     * 
     * @return void
     */
    public function use($path, $callback, $method = 'ALL')
    {
        $route = new Route([
            'path' => $path,
            'callback' => $callback,
            'method' => $method
        ]);

        $this->addRoute($route);
    }

    /**
     * Add a route to the routes collection.
     * 
     * @param Route $route
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
        return $this;
    }
    
    /**
     * Shorthand method to add a 'GET' route.
     * 
     * @param string $path
     * @param mixed $callback
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function get($path, $callback)
    {
        $this->use($path, $callback, 'GET');
        return $this;
    }

    /**
     * Shorthand method to add a 'POST' route.
     * 
     * @param string $path
     * @param mixed $callback
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function post($path, $callback)
    {
        $this->use($path, $callback, 'POST');
    }

    /**
     * Shorthand method to add a 'PUT' route.
     * 
     * @param string $path
     * @param mixed $callback
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function put($path, $callback)
    {
        $this->use($path, $callback, 'PUT');
    }

    /**
     * Shorthand method to add a 'DELETE' route.
     * 
     * @param string $path
     * @param mixed $callback
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function delete($path, $callback)
    {
        $this->use($path, $callback, 'DELETE');
    }

    /**
     * Shorthand method to add a 'PATCH' route.
     * 
     * @param string $path
     * @param mixed $callback
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function patch($path, $callback)
    {
        $this->use($path, $callback, 'PATCH');
    }
}