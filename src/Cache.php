<?php

namespace duncan3dc\Helpers;

class Cache
{
    private static $instances = [];
    private static $data = [];


    public static function getInstance($name)
    {
        if (!isset(static::$instances[$name])) {
            static::$instances[$name] = new CacheInstance();
        }
        return static::$instances[$name];
    }


    public static function check($key)
    {
        if (array_key_exists($key, static::$data)) {
            return true;
        }

        return false;
    }


    public static function get($key)
    {
        return static::$data[$key];
    }


    public static function set($key, $val)
    {
        static::$data[$key] = $val;

        return true;
    }


    public static function clear($key = null)
    {
        if ($key) {
            unset(static::$data[$key]);
        } else {
            static::$data = [];
        }

        return true;
    }


    public static function call($key, callable $func)
    {
        $trace = debug_backtrace();
        if ($function = $trace[1]["function"]) {
            $key = $function . "::" . $key;
            if ($class = $trace[1]["class"]) {
                $key = $class . "::" . $key;
            }
        }

        if (static::check($key)) {
            return static::get($key);
        }

        $return = $func();

        static::set($key, $return);

        return $return;
    }
}
