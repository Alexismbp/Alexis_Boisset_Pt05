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
        // Comprobar si es una ruta de archivo estático
        if (strpos($uri, '/uploads/') === 0 || 
            strpos($uri, '/assets/') === 0 || 
            strpos($uri, '/views/') === 0) {
            $filePath = BASE_PATH . ltrim($uri, '/');
            if (file_exists($filePath)) {
                // Establecer el tipo MIME correcto
                $mime = mime_content_type($filePath);
                header('Content-Type: ' . $mime);
                readfile($filePath);
                exit;
            }
        }

        $method = $_SERVER['REQUEST_METHOD'];
        
        // Limpiar la URI de parámetros de consulta
        $uri = parse_url($uri, PHP_URL_PATH);

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
                    
                    // Almacenar el ID en $_GET para compatibilidad
                    $_GET['id'] = $matches[1];
                    
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
        // Escapar caracteres especiales
        $route = preg_quote($route, '#');
        // Reemplazar el parámetro con un patrón de captura
        $route = preg_replace('/\\\{([a-zA-Z]+)\\\}/', '([^/]+)', $route);
        return '#^' . $route . '$#';
    }

    private function extractParamName($route)
    {
        if (preg_match('/\{([a-zA-Z]+)\}/', $route, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
?>
