<?php

namespace Library;

use Library\Services\Databases\AbstractDatabase;

/**
 * Description of Router
 *
 * @author H1
 */
class Router
{
    protected $routes = [];
    
    public function __construct(
        private AbstractDatabase $database
    ) {}

    private function addRoute($route, $controller, $action, $method)
    {

        $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    public function get($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "GET");
    }

    public function post($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "POST");
    }

    public function dispatch()
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method = $_SERVER['REQUEST_METHOD'];

        if (array_key_exists($uri, $this->routes[$method])) {
            $controllerName = $this->routes[$method][$uri]['controller'];
            $action = $this->routes[$method][$uri]['action'];

            $request = new Request();
            $controller = new $controllerName($request, $this->database);
            $controller->{$action}();
        } else {
            throw new \Exception("No route found for URI: $uri");
        }
    }
}
