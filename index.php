<!-- Alexis Boisset -->
<?php
// FILE: index.php

// Incluir archivos necesarios
require_once "models/env.php";
require_once "models/database/database.model.php";
require_once "controllers/session/session.controller.php";

session_start();

// Obtener la ruta solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si la aplicación trabaja en un subdirectorio, descomentar la siguiente línea

// $uri = substr($uri, strlen(BASE_URL));

// Enrutamiento básico
if ($uri === '/' || $uri === '') {
    // Ruta principal
    include __DIR__ . '/controllers/main.controller.php';
} elseif ($uri === '/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include __DIR__ . '/controllers/auth/login.controller.php';
    } else {
        include __DIR__ . '/views/auth/login.view.php';
    }
} elseif ($uri === '/logout') {
    include __DIR__ . '/controllers/auth/logout.controller.php';
} elseif ($uri === '/register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include __DIR__ . '/controllers/auth/register.controller.php';
    } else {
        include __DIR__ . '/views/auth/register.view.php';
    }
} elseif ($uri === '/create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include __DIR__ . '/controllers/crud/create.controller.php';
    } else {
        include __DIR__ . '/views/crud/create.view.php';
    }
} elseif ($uri === '/delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include __DIR__ . '/controllers/crud/delete.controller.php';
    } else {
        include __DIR__ . '/views/crud/delete.view.php';
    }
} elseif ($uri === '/forgotpassword') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include __DIR__ . '/controllers/auth/forgotpassword.controller.php';
    } else {
        include __DIR__ . '/views/auth/forgotpassword.view.php';
    }
} else {
    // Enviar el encabezado 404
    header("HTTP/1.0 404 Not Found");
    exit();
}
?>