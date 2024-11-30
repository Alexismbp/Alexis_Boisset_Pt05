<?php
class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch($uri)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];

            if ($handler instanceof Closure) {
                return $handler();
            }

            return $handler;
        }

        throw new Exception('Route not found');
    }
}
