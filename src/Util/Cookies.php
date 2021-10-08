<?php

namespace Makiavelo\Quark\Util;

use Makiavelo\Quark\Util\Common;

/**
 * Simple wrapper around $_COOKIE and cookies creation
 * 
 * Get a session via: Cookies::get(); // Singleton
 * 
 * Param getters via 'Common::get'
 * Example: $cookies->param('some->path->var', $default);
 * 
 * Send a cookie via 'Cookies::get()->send($params)'
 * same parameters as 'setcookie' function but in an associative array
 */
class Cookies
{
    protected static $instance;

    protected function __construct()
    {
        
    }

    /**
     * Get the current instance or create one.
     * 
     * @return Cookies
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new Cookies();
        }

        return self::$instance;
    }

    /**
     * Recreate the session instance.
     * 
     * @return Cookies
     */
    public static function resetInstance()
    {
        self::$instance = new Cookies();
        return self::$instance;
    }

    /**
     * Get a $_COOKIE value
     * 
     * @param mixed $path
     * @param mixed $default
     * 
     * @return mixed
     */
    public function param($path, $default = null)
    {
        return Common::get($_COOKIE, $path, $default);
    }

    /**
     * Send a cookie via 'setcookie'
     * 
     * @param mixed $params
     * 
     * @return boolean
     */
    public function send($params)
    {
        $final = array_merge($this->getDefaultCookieParams(), $params);

        if ($final['raw']) {
            // Send a cookie without urlencoding the cookie value
            $status = setrawcookie(
                $final['name'],
                $final['value'],
                $final['expires'],
                $final['path'],
                $final['domain'],
                $final['secure'],
                $final['httponly']
            );
        } else {
            // Send a cookie with url encoded value
            $status = setcookie(
                $final['name'],
                $final['value'],
                $final['expires'],
                $final['path'],
                $final['domain'],
                $final['secure'],
                $final['httponly']
            );
        }

        return $status;
    }

    /**
     * Get Default values for 'setcookie' in array format.
     * 
     * @return array
     */
    public function getDefaultCookieParams()
    {
        return [
            'name' => "",
            'value' => "",
            'expires' => 0,
            'path' => "",
            'domain' => "",
            'secure' => false,
            'httponly' => false,
            'raw' => false
        ];
    }
}