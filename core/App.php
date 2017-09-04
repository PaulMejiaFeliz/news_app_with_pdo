<?php namespace newsapp\core;

/**
 * Class that contains dependencies for been injected
 */
class App
{
    /**
     * Array of the dependencies for been injected
     *
     * @var array
     */
    private static $dependecies = [];

    /**
     * Sets a new dependency
     *
     * @param string $key Identifier for the dependency
     * @param mixed $value Value of the dependency
     * @return void
     */
    public static function bind(string $key, $value) : void
    {
        static::$dependecies[$key] = $value;
    }
    
    /**
     * Retrieves the dependency that matches the given key
     *
     * @param string $key Key of the dependency
     * @return mixed Value of the dependency
     */
    public static function get(string $key)
    {
        if (array_key_exists($key, static::$dependecies)) {
            return static::$dependecies[$key];
        }
    }
}
