<?php

namespace Makiavelo\Quark;

use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;
use Makiavelo\Quark\Router;

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
        if ($route->type === 'renderer') {
            if (!$this->response->hasRendered()) {
                $this->request->addPathParams($route->params);
                $result = call_user_func($route->callback, $this->request, $this->response);

                if ($result === false) {
                    $route->continue = false;
                }
            }
        } else {
            $result = call_user_func($route->callback, $this->request, $this->response);
            if ($result === false) {
                $route->continue = false;
            }
        }
    }

    /**
     * Add a routed middleware to the stack.
     * 
     * @param string $path
     * @param mixed $callback
     * @param string $params
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function middleware($path, $callback, $params = [])
    {
        $merged = array_merge(Route::getDefaultParams(), $params);
        $route = new Route([
            'path' => $path,
            'callback' => $callback,
            'method' => $merged['method'],
            'type' => $merged['type']
        ]);

        $this->addRoute($route);
        return $this;
    }

    /**
     * Add a routed middleware to the stack.
     * 
     * @param string $path
     * @param mixed $callback
     * @param string $method
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function use($path, $callback, $method = 'ALL')
    {
        $params = [
            'method' => $method,
            'type' => 'simple'
        ];
        $this->middleware($path, $callback, $params);
        return $this;
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
     * Add a router
     * 
     * @param Route $route
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function addRouter(Router $router)
    {
        if ($router->routes()) {
            foreach ($router->routes() as $route) {
                $this->middleware($route->path, $route->callback, ['method' => $route->method]);
            }
        }

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
        $this->middleware($path, $callback, ['method' => 'GET']);
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
        $this->middleware($path, $callback, ['method' => 'POST']);
        return $this;
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
        $this->middleware($path, $callback, ['method' => 'PUT']);
        return $this;
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
        $this->middleware($path, $callback, ['method' => 'DELETE']);
        return $this;
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
        $this->middleware($path, $callback, ['method' => 'PATCH']);
        return $this;
    }

    /**
     * Shorthand method for ALL methods
     * 
     * @param string $path
     * @param mixed $callback
     * 
     * @return Makiavelo\Quark\Quark
     */
    public function all($path, $callback)
    {
        $this->middleware($path, $callback, ['method' => 'ALL']);
        return $this;
    }
}