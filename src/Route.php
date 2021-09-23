<?php

namespace Makiavelo\Quark;

use Makiavelo\Quark\Request;
use Makiavelo\Quark\Quark;

class Route
{
    public $name;
    public $action;
    public $path;
    public $method;
    public $matched;
    public $params;

    /**
     * Create a new Route.
     * 
     * @param mixed $params
     */
    public function __construct($params)
    {
        $this->callback = isset($params['callback']) ? $params['callback'] : null;
        $this->path = $params['path'];
        $this->method = $params['method'];
        $this->matched = false;
        $this->params = [];
        $this->continue = true;
    }

    /**
     * Check if the route matches the current request
     * 
     * @return boolean
     */
    public function match()
    {
        $path = Quark::app()->request->path();
        $method = Quark::app()->request->method();

        if ($method === $this->method) {
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($this->path)) . "$@D";
            $matches = [];
            
            $status = preg_match($pattern, $path, $matches);

            if ($status === 1) {
                if ($matches) {
                    array_shift($matches);
                    $paramNames = $this->getNamedParams();
                    foreach ($paramNames as $key => $name) {
                        $this->params[$name] = $matches[$key];
                    }
                }

                return true;
            }
        }
        
        return false;
    }

    /**
     * Get all the parameter names from the url
     * Eg: /user/:id/edit
     * would return ['id']
     * 
     * @return array
     */
    public function getNamedParams()
    {
        $matches = [];
        preg_match_all('/\/:([a-zA-Z0-9\_\-]+)/', $this->path, $matches);

        if ($matches) {
            return $matches[1];
        } else {
            return [];
        }
    }
}