<?php
require_once __DIR__ . "/models/env.php";
require_once __DIR__ . "/models/database/database.model.php";
require_once __DIR__ . "/controllers/session/session.controller.php";
require_once __DIR__ . "/core/Router.php";

session_start();

$router = new Router();

// Definir rutas GET
$router->get('/', 'controllers/main.controller.php');
$router->get('/login', 'views/auth/login/login.view.php');
$router->get('/register', 'views/auth/login/register.view.php');
$router->get('/create', 'views/crud/create.view.php');
$router->get('/delete', 'views/crud/delete.view.php');
$router->get('/forgotpassword', 'views/auth/forgotpassword.view.php');

// Definir rutas POST
$router->post('/login', 'controllers/auth/login.controller.php');
$router->post('/register', 'controllers/auth/register.controller.php');
$router->post('/create', 'controllers/crud/create.controller.php');
$router->post('/delete', 'controllers/crud/delete.controller.php');
$router->post('/forgotpassword', 'controllers/auth/forgotpassword.controller.php');
$router->get('/logout', 'controllers/auth/logout.controller.php');

// Obtener y procesar la URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$uri = "/" . ltrim(substr($uri, strlen($basePath)), '/');

try {
    $controller = $router->dispatch($uri);
    include BASE_PATH . $controller;
} catch (Exception $e) {
    http_response_code(404);
    include BASE_PATH . 'views/errors/404.view.php';
}
?>