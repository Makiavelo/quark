<?php

namespace Makiavelo\Quark\Util;

use Makiavelo\Quark\Util\Common;

/**
 * Simple wrapper around $_SESSION
 * 
 * Get a session via: Session::get(); // Singleton
 * 
 * All the session functions can be called via 'method'
 * Example: $session->method('start'); // Will call session_start()
 *          $session->method('create_id', ['preffix']); // session_create_id('preffix')
 * 
 * Param getters via 'Common::get'
 * Example: $session->param('some->path->var', $default);
 * 
 * Set parameters via 'Common::set'
 * Example $session->set('user->tags->0', 'Some tag');
 */
class Session
{
    protected static $instance;

    protected function __construct()
    {
        
    }

    /**
     * Get the current instance or create one.
     * 
     * @return Session
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new Session();
        }

        return self::$instance;
    }

    /**
     * Recreate the session instance.
     * 
     * @return Session
     */
    public static function resetInstance()
    {
        self::$instance = new Session();
        return self::$instance;
    }

    /**
     * Get a session parameter
     * 
     * @param mixed $path
     * @param mixed $default
     * 
     * @return mixed
     */
    public function param($path, $default = null)
    {
        return Common::get($_SESSION, $path, $default);
    }

    /**
     * Set a session parameter
     * 
     * @param mixed $path
     * @param mixed $value
     * 
     * @return Session
     */
    public function set($path, $value)
    {
        $new = Common::set($_SESSION, $path, $value);
        $_SESSION = $new;
        return $this;
    }

    /**
     * Start a session with session_start()
     * 
     * @return Session
     */
    public function start()
    {
        $this->method('start');
        return $this;
    }

    /**
     * Destroy a session with session_destroy()
     * 
     * @return Session
     */
    public function destroy()
    {
        $this->method('destroy');
        return $this;
    }

    /**
     * Call any session_* method with parameters (if any)
     * 
     * @param mixed $name
     * @param array $params
     * 
     * @return mixed
     */
    public function method($name, $params = [])
    {
        $method = 'session_' . $name;
        return call_user_func($method, $params);
    }
}