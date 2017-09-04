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

    /**
     * Adds the given key-value peers to the current queryString
     *
     * @param array $queryStrings array of key-value peers that will be added
     * @return string The current URI and query string and the given key-values peers
     */
    public static function addQueryString(array $queryStrings) : string
    {
        $url = Request::queryString();
        if (!is_null($url)) {
            $currentQueryStrings = explode('&', $url);
            $url = '';
            foreach ($currentQueryStrings as $queryString) {
                $string = explode('=', $queryString);
                if ($string[0] != '' && isset($string[1])) {
                    $keys[] = $string[0];
                    if (array_key_exists($string[0], $queryStrings)) {
                        $url .= "{$string[0]}={$queryStrings[$string[0]]}&";
                        unset($queryStrings[$string[0]]);
                    } elseif ($string[1] != '') {
                        $url .= "{$string[0]}={$string[1]}&";
                    }
                }
            }
        }

        foreach ($queryStrings as $key => $value) {
            $url .= "{$key}={$value}&";
        }

        return Request::uri() . '?' . trim($url, '&');
    }
}
