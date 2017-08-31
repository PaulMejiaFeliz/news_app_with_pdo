<?php

class App
{
    protected static $dependecies = [];

    public static function bind(string $key, $value)
    {
        static::$dependecies[$key] = $value;
    }
    
    public static function get(string $key)
    {
        if (array_key_exists($key, static::$dependecies)) {
            return static::$dependecies[$key];
        }
    }
}