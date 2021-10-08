<?php

namespace Makiavelo\Quark;

use Makiavelo\Quark\Route;

/**
 * Class to group 'Route' objects
 * 
 * The idea behind the router is that you can group a set of routes
 * for example '/user/edit', '/user/view', etc, in a user route class
 * to improve readability.
 */
class Router
{
    /**
     * @var Route[]
     */
    public $routes;
    public $basePath;

    /**
     * A preffix added to each route
     * 
     * @param string $basePath
     */
    public function __construct($basePath = '')
    {
        $this->basePath = $basePath;
        $this->init();
    }

    public function init()
    {
        // Override this method if needed
    }

    /**
     * Add a route to the collection
     * 
     * @param Route $route
     * 
     * @return Router
     */
    public function add(Route $route)
    {
        $route->path = $this->basePath . $route->path;
        $this->routes[] = $route;
        return $this;
    }

    /**
     * Remove a route from the collection by 'path'
     * 
     * @param string $path
     * 
     * @return Router
     */
    public function remove($path)
    {
        foreach ($this->routes as $key => $route) {
            if ($route->path === $this->basePath . $path) {
                unset($this->routes[$key]);
            }
        }

        return $this;
    }

    /**
     * Empty the routes collection
     * 
     * @return Router
     */
    public function clear()
    {
        $this->routes = [];
        return $this;
    }

    /**
     * Return the routes collection
     * @return Route[]
     */
    public function routes()
    {
        return $this->routes;
    }
}