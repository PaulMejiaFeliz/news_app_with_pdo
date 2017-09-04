<?php namespace newsapp\core;

/**
 * Class use to call the action that match the request
 */
class Router
{
    /**
     * Array of the posibles requests
     *
     * @var array
     */
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'ERROR' => ''
    ];
    
    /**
     *
     * @param string $errorPage sets the default error page
     */
    public function __construct(string $errorPage)
    {
        $this->routes['ERROR'] = $errorPage;
    }

    /**
     * Set an action for a GET request
     *
     * @param string $uri URI of the request
     * @param string $action action for the given URI
     * @return void
     */
    public function get(string $uri, string $action) : void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Set an action for a POST request
     *
     * @param string $uri URI of the request
     * @param string $action action for the given URI
     * @return void
     */
    public function post(string $uri, string $action) : void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Set an action for a PUT request
     *
     * @param string $uri URI of the request
     * @param string $action action for the given URI
     * @return void
     */
    public function put(string $uri, string $action) : void
    {
        $this->routes['PUT'][$uri] = $action;
    }

    /**
     * Set an action for a DELETE request
     *
     * @param string $uri URI of the request
     * @param string $action action for the given URI
     * @return void
     */
    public function delete(string $uri, string $action) : void
    {
        $this->routes['DELETE'][$uri] = $action;
    }

    /**
     * Finds and calls the action that matches the given request
     *
     * @param string $uri URI of the request
     * @param string $method Method of the request
     * @return void
     */
    public function direct(string $uri, string $method = 'GET') : void
    {
        if (array_key_exists($uri, $this->routes[$method])) {
            $this->action(
                ...explode('@', $this->routes[$method][$uri])
            );
            return;
        }
        $this->action(...explode('@', $this->routes['ERROR']));
    }

    /**
     * Check if the given action exist and calls it, otherwise calls the default error action
     *
     * @param string $controller Controller where the action is
     * @param string $action Action that will be called
     * @return void
     */
    private function action(string $controller, string $action) : void
    {
        $controller = "newsapp\\controllers\\{$controller}Controller";
        $controller = new $controller();

        if (method_exists($controller, $action)) {
            $controller->$action();
            return;
        }
        
        $error = explode('@', $this->routes['ERROR']);
        $controller = "newsapp\\controllers\\{$error[0]}Controller";

        $controller = new $controller();
        
        if (method_exists($controller, $error[1])) {
            $controller->$error[1]();
        }
        
    }
}
