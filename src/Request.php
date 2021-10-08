<?php

namespace Makiavelo\Quark;

use Makiavelo\Quark\Util\Common;

class Request
{
    protected static $instance;
    public $params;
    public $pathParams;
    public $post;
    public $query;

    protected function __construct()
    {
        $this->initParams();
    }

    /**
     * Initialize instance values based on superglobals.
     * 
     * @return void
     */
    public function initParams()
    {
        $this->pathParams = [];
        $this->params = $_REQUEST;
        $this->post = $_POST;
        $this->query = $_GET;
    }

    /**
     * Get the current instance or create one.
     * 
     * @return Makiavelo\Quark\Request
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new Request();
        }

        return self::$instance;
    }

    /**
     * Recreate the request instance.
     * 
     * @return Makiavelo\Quark\Request
     */
    public static function resetInstance()
    {
        self::$instance = new Request();
        return self::$instance;
    }

    /**
     * Get the current url's path.
     * Eg: https://somesite.com/path/to/url
     *     The path would be '/path/to/url'
     * 
     * @return string
     */
    public function path()
    {
        $path = Common::get($_SERVER, 'REQUEST_URI');
        $path = parse_url($path, PHP_URL_PATH);
        if (!$path) {
            $path = Common::get($_SERVER, 'PATH_INFO', '');
        }

        return $path;
    }

    /**
     * Get the current request's method from superglobals.
     * 
     * @return string
     */
    public static function method() {
        $method = Common::get($_SERVER, 'REQUEST_METHOD', 'GET');
        return strtoupper($method);
    }

    /**
     * Get a variable from the parameters bag.
     * 
     * @param mixed $name
     * @param null $default
     * 
     * @return mixed
     */
    public function param($name, $default = null)
    {
        return Common::get($this->params, $name, $default);
    }

    /**
     * Get a parameter from the POST variables ($_POST)
     * 
     * @param mixed $name
     * @param null $default
     * 
     * @return mixed
     */
    public function post($name, $default = null)
    {
        return Common::get($this->post, $name, $default);
    }

    /**
     * Get a parameter from the querystring ($_GET)
     * 
     * @param mixed $name
     * @param null $default
     * 
     * @return mixed
     */
    public function query($name, $default = null)
    {
        return Common::get($this->query, $name, $default);
    }

    /**
     * Adds the parameters found in the URL via rewrites.
     * Eg: /users/:id/edit (param id = 1)
     * 
     * @param array $params
     * 
     * @return void
     */
    public function addPathParams($params = [])
    {
        if ($params) {
            $this->pathParams = $params;
            $this->params = array_merge($this->params, $params);
        }
    }

    /**
     * Get the current request's scheme from superglobals.
     * 
     * @return string
     */
    public static function getScheme() {
        if (
            (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on')
            ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            ||
            (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && $_SERVER['HTTP_FRONT_END_HTTPS'] === 'on')
            ||
            (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')
        ) {
            return 'https';
        }
        return 'http';
    }

    /**
     * Get the request body
     * 
     * @return mixed
     */
    public function getBody() {
        $method = $this->method();

        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE' || $method == 'PATCH') {
            $body = file_get_contents('php://input');
        }

        return $body;
    }
}