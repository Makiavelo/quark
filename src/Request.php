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

    public function initParams()
    {
        $this->pathParams = [];
        $this->params = $_REQUEST;
        $this->post = $_POST;
        $this->query = $_GET;
    }

    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new Request();
        }

        return self::$instance;
    }

    public static function resetInstance()
    {
        self::$instance = new Request();
        return self::$instance;
    }

    public function path()
    {
        $path = Common::get($_SERVER, 'PATH_INFO', '');
        return $path;
    }

    public static function method() {
        $method = Common::get($_SERVER, 'REQUEST_METHOD', 'GET');
        return strtoupper($method);
    }

    public function param($name, $default = null)
    {
        return Common::get($this->params, $name, $default);
    }

    public function post($name, $default = null)
    {
        return Common::get($this->post, $name, $default);
    }

    public function query($name, $default = null)
    {
        return Common::get($this->query, $name, $default);
    }

    public function addPathParams($params = [])
    {
        if ($params) {
            $this->pathParams = $params;
            $this->params = array_merge($this->params, $params);
        }
    }

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

    public function getBody() {
        static $body;

        if (!is_null($body)) {
            return $body;
        }

        $method = $this->method();

        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE' || $method == 'PATCH') {
            $body = file_get_contents('php://input');
        }

        return $body;
    }
}