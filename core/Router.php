<?php

class Router
{
    protected $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => [],
        "ERROR" => ""
    ];
    
    public function __construct($errorPage)
    {
        $this->routes["ERROR"] = $errorPage;
    }

    public function get($uri, $controller)
    {
        $this->routes["GET"][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes["POST"][$uri] = $controller;
    }

    public function put($uri, $controller)
    {
        $this->routes["PUT"][$uri] = $controller;
    }

    public function delete($uri, $controller)
    {
        $this->routes["DELETE"][$uri] = $controller;
    }

    public function direct($uri, $method = "GET")
    {
        if (array_key_exists($uri, $this->routes[$method])) {
            return $this->action(
                ...explode('@', $this->routes[$method][$uri])
            );
        } else {
            return $this->action(...explode('@', $this->routes['ERROR']));
        }
    }

    protected function action($controller, $action)
    {
        $controller .= "Controller";
        $controller = new $controller;

        if (method_exists($controller, $action)) {
            return $controller->$action();
        }
        return $this->action(...explode('@', $this->routes['ERROR']));
    }
}
