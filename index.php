<?php
// Alexis Boisset

// FILE: index.php

// Incluir archivos necesarios
require_once __DIR__ . "/models/env.php";
require_once __DIR__ . "/models/database/database.model.php";
require_once __DIR__ . "/controllers/session/session.controller.php";

session_start();

// Obtener la ruta solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remover el prefijo de BASE_URL para obtener la ruta relativa
$basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$uri = "/" . ltrim(substr($uri, strlen($basePath)), '/');

// Enrutamiento básico
if ($uri === '/' || $uri === '') {
    // Ruta principal
    include BASE_PATH . 'controllers/main.controller.php';
} elseif ($uri === '/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . 'controllers/auth/login.controller.php';
    } else {
        include BASE_PATH . 'views/auth/login/login.view.php';
    }
} elseif ($uri === '/logout') {
    include BASE_PATH . 'controllers/auth/logout.controller.php';
} elseif ($uri === '/register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . 'controllers/auth/register.controller.php';
    } else {
        include BASE_PATH . 'views/auth/register.view.php';
    }
} elseif ($uri === '/create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . 'controllers/crud/create.controller.php';
    } else {
        include BASE_PATH . 'views/crud/create.view.php';
    }
} elseif ($uri === '/delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . 'controllers/crud/delete.controller.php';
    } else {
        include BASE_PATH . 'views/crud/delete.view.php';
    }
} elseif ($uri === '/forgotpassword') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . 'controllers/auth/forgotpassword.controller.php';
    } else {
        include BASE_PATH . 'views/auth/forgotpassword.view.php';
    }
} else {
    // Enviar el encabezado 404 y mostrar la página de error
    http_response_code(404);
    include BASE_PATH . 'views/errors/404.view.php';
    exit();
}
?>