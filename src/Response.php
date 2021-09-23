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

    /**
     * Get the current instance or create one
     * 
     * @return Makiavelo\Quark\Response
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new Response();
        }

        return self::$instance;
    }

    /**
     * Re-create the current instance
     * 
     * @return Makiavelo\Quark\Response
     */
    public static function resetInstance()
    {
        self::$instance = new Response();
        return self::$instance;
    }

    /**
     * Set the body of the response or return the current body.
     * 
     * @param mixed $content
     * 
     * @return string|Makiavelo\Quark\Response
     */
    public function body($content = null)
    {
        if (!$content) {
            return $this->body;
        } else {
            $this->body = $content;
            return $this;
        }
    }

    /**
     * Get or set the status of the response.
     * 
     * @param mixed $code
     * 
     * @return Makiavelo\Quark\Response
     */
    public function status($code = null)
    {
        if ($code === null) return $this->status;
        $this->status = $code;
        return $this;
    }

    /**
     * Add a list of headers to be sent in the response.
     * 
     * @param array $headers
     * 
     * @return Makiavelo\Quark\Response
     */
    public function addHeaders($headers)
    {
        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }

        return $this;
    }

    /**
     * Add a header to be sent in the response.
     * 
     * @param mixed $name
     * @param mixed $value
     * 
     * @return Makiavelo\Quark\Response
     */
    public function addHeader($name, $value)
    {
        $this->headers[] = [
            $name => $value
        ];

        return $this;
    }

    /**
     * Reset the status, header and body of the response.
     * 
     * @return Makiavelo\Quark\Response
     */
    public function clear()
    {
        $this->status = 200;
        $this->headers = [];
        $this->body = '';

        return $this;
    }

    /**
     * Send the headers collection to the client
     * 
     * @return Makiavelo\Quark\Response
     */
    public function sendHeaders()
    {
        if ($this->headers) {
            foreach ($this->headers as $name => $value) {
                header($name.': '.$value);
            }
        }

        return $this;
    }

    /**
     * Send the response to the client
     * 
     * @param string $body
     * 
     * @return void
     */
    public function send($body = '')
    {
        if ($body) {
            $this->body = $body;
        }
        
        $this->sendHeaders();
        echo $this->body;
    }
}
