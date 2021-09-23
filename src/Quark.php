<?php

namespace Makiavelo\Quark;

use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

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

    public static function app()
    {
        if (!self::$instance) {
            self::$instance = new Quark();
        }

        return self::$instance;
    }

    public static function resetInstance()
    {
        self::$instance = new Quark();
        return self::$instance;
    }

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

    public function applyRoute(Route $route)
    {
        $this->request->addPathParams($route->params);
        call_user_func($route->callback, $this->request, $this->response);
    }

    public function use($path, $callback, $method = 'ALL')
    {
        $route = new Route([
            'path' => $path,
            'callback' => $callback,
            'method' => $method
        ]);

        $this->addRoute($route);
    }

    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    // Method aliases for 'use'
    public function get($path, $callback)
    {
        $this->use($path, $callback, 'GET');
    }

    public function post($path, $callback)
    {
        $this->use($path, $callback, 'POST');
    }

    public function put($path, $callback)
    {
        $this->use($path, $callback, 'PUT');
    }

    public function delete($path, $callback)
    {
        $this->use($path, $callback, 'DELETE');
    }

    public function patch($path, $callback)
    {
        $this->use($path, $callback, 'PATCH');
    }
}