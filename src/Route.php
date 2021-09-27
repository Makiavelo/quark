<?php

namespace Makiavelo\Quark;

use Makiavelo\Quark\Quark;

class Route
{
    public $name;
    public $action;
    public $path;
    public $method;
    public $matched;
    public $params;
    public $type;

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
        $this->type = isset($params['type']) ? $params['type'] : 'renderer';
    }

    /**
     * Get the default parameters for creating a route
     * 
     * @return array
     */
    public static function getDefaultParams()
    {
        $params = [
            'method' => 'ALL',
            'type' => 'renderer'
        ];

        return $params;
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

        if ($method === $this->method || $this->method === 'ALL') {
            // Replace the route path parameters (@param_name) for a pattern
            // Basically every parameter gets converted to ([a-zA-Z0-9\-\_]+)
            $pattern = "&^" . preg_replace('/@[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', $this->path) . "$&D";
            
            $matches = [];
            $status = preg_match($pattern, $path, $matches);

            if ($status === 1) {
                if ($matches) {
                    // If there are matched parameters, store the key/value pairs
                    // The first result is shifted because it's the full string
                    array_shift($matches);

                    $paramNames = $this->getNamedParams();
                    foreach ($paramNames as $key => $name) {
                        // Pair the named parameters of the route path with the values
                        // we get from the url (same order)
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
        preg_match_all('/@([a-zA-Z0-9\_\-]+)/', $this->path, $matches);

        if ($matches) {
            return $matches[1];
        } else {
            return [];
        }
    }
}