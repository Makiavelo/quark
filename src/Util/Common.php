<?php

namespace Makiavelo\Quark\Util;

class Common
{
    public static function get($collection = [], $key = null, $default = null)
    {
        if ($key === null || $key === false) {
            return $collection;
        }

        if (!is_object($collection) && isset($collection[$key])) {
            return $collection[$key];
        }

        foreach (explode('->', $key) as $segment) {
            if (is_object($collection)) {
                if (!isset($collection->{$segment})) {
                    return $default;
                } else {
                    $collection = $collection->{$segment};
                }
            } else {
                if (!isset($collection[$segment])) {
                    return $default;
                } else {
                    $collection = $collection[$segment];
                }
            }
        }

        return $collection;
    }

    public static function set($collection, $key, $value = null, $container = 'array')
    {
        $parts = explode('->', $key);
        $count = count($parts);
        $segment = array_shift($parts);

        if ($count === 1) {
            if (is_object($collection)) {
                $collection->{$segment} = $value;
            } else {
                $collection[$segment] = $value;
            }
        } else {
            if (is_object($collection)) {
                $collection->{$segment} = $container === 'array' ? [] : new \stdClass();
                $collection->{$segment} = Common::set($collection->{$segment}, implode('->', $parts), $value, $container);
            } else {
                $collection[$segment] = $container === 'array' ? [] : new \stdClass();
                $collection[$segment] = Common::set($collection[$segment], implode('->', $parts), $value, $container);
            }
        }
        
        return $collection;
    }

    public static function find($collection, $key, $value, $multiple = false)
    {
        $return = $multiple ? [] : null;

        if ($collection) {
            foreach ($collection as $elem) {
                $match = false;
                $elemValue = Common::get($elem, $key);
                if (is_array($value) && in_array($value, $elemValue)) {
                    $match = true;
                } elseif ($value === $elemValue) {
                    $match = true;
                }

                if ($match) {
                    if ($multiple) {
                        $return[] = $elem;
                    } else {
                        return $elem;
                    }
                }
            }
        }

        return $return;
    }
}