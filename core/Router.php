<?php
/**
 * Router class for handling HTTP routes and requests
 */
class Router
{
    /** @var array Array to store routes and their handlers */
    private $routes = [];
    
    /** @var array Array to store route parameters */
    private $params = [];

    /**
     * Registers a GET route with its handler
     * @param string $path The URL path to match
     * @param callable $handler The function to execute when route matches
     */
    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Registers a POST route with its handler
     * @param string $path The URL path to match
     * @param callable $handler The function to execute when route matches
     */
    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Dispatches the request to the appropriate handler based on the URI
     * @param string $uri The request URI to process
     * @return callable The matched route handler
     * @throws Exception When no route matches the URI
     */
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

    /**
     * Gets a parameter value by name from the matched route
     * @param string $name The parameter name to retrieve
     * @return string|null The parameter value or null if not found
     */
    public function getParam($name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * Converts a route pattern to a regular expression
     * @param string $route The route pattern to convert
     * @return string The regular expression pattern
     * @private
     */
    private function convertRouteToRegex($route)
    {
        // Escapar caracteres especiales
        $route = preg_quote($route, '#');
        // Reemplazar el parámetro con un patrón de captura
        $route = preg_replace('/\\\{([a-zA-Z]+)\\\}/', '([^/]+)', $route);
        return '#^' . $route . '$#';
    }

    /**
     * Extracts parameter name from a route pattern
     * @param string $route The route pattern to analyze
     * @return string|null The parameter name or null if not found
     * @private
     */
    private function extractParamName($route)
    {
        if (preg_match('/\{([a-zA-Z]+)\}/', $route, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
?>
