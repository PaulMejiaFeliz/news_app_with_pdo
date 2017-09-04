<?php namespace newsapp\core;

/**
 * Class used to get data from the current request
 */
class Request
{
    /**
     * Returns the URI of the current request
     *
     * @return string URI of the current request
     */
    public static function uri() : string
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }
    
    /**
     * Returns the query string of the current request
     *
     * @return string Query string of the current request
     */
    public static function queryString() : string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '';
    }

    /**
     * Returns the method of the current request
     *
     * @return string Method of the current request
     */
    public static function method() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
