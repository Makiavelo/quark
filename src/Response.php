<?php

namespace Makiavelo\Quark;

class Response
{
    public $status = 200;
    public $headers = [];
    public $body;

    protected static $instance;

    protected function __construct()
    {

    }

    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new Response();
        }

        return self::$instance;
    }

    public static function resetInstance()
    {
        self::$instance = new Response();
        return self::$instance;
    }

    public function body($content = null)
    {
        if (!$content) {
            return $this->body;
        } else {
            $this->body = $content;
            return $this;
        }
    }

    public function status($code = null)
    {
        if ($code === null) return $this->status;
        $this->status = $code;
        return $this;
    }

    public function addHeaders($headers)
    {
        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }
    }

    public function addHeader($name, $value)
    {
        $this->headers[] = [
            $name => $value
        ];

        return $this;
    }

    public function clear()
    {
        $this->status = 200;
        $this->headers = [];
        $this->body = '';
    }

    public function sendHeaders()
    {
        if ($this->headers) {
            foreach ($this->headers as $name => $value) {
                header($name.': '.$value);
            }
        }
    }

    public function send($body = '')
    {
        if ($body) {
            $this->body = $body;
        }
        
        $this->sendHeaders();
        echo $this->body;
    }
}
