<?php
class Router
{
    private $routes = [];
    private $params = [];

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

        // Buscar coincidencia exacta primero
        if (isset($this->routes[$method][$uri])) {
            return $this->routes[$method][$uri];
        }

        // Si no hay coincidencia exacta, buscar rutas con parámetros
        foreach ($this->routes[$method] as $route => $handler) {
            if (strpos($route, '{') !== false) {
                $pattern = $this->convertRouteToRegex($route);
                if (preg_match($pattern, $uri, $matches)) {
                    // Extraer el valor del parámetro
                    $paramName = $this->extractParamName($route);
                    $this->params[$paramName] = $matches[1];
                    return $handler;
                }
            }
        }

        throw new Exception('Route not found');
    }

    public function getParam($name)
    {
        return $this->params[$name] ?? null;
    }

    private function convertRouteToRegex($route)
    {
        return '#^' . preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $route) . '$#';
    }

    private function extractParamName($route)
    {
        preg_match('/\{([a-zA-Z]+)\}/', $route, $matches);
        return $matches[1];
    }
}
