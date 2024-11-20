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

// Remover el prefijo si tu aplicación está en un subdirectorio
$baseDir = ''; // Por ejemplo, '/Practiques/M07-Servidor/Alexis_Boisset_Pt05'
$uri = substr($uri, strlen($baseDir));

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
} else {
    // Página 404 si la ruta no coincide
    include __DIR__ . '/views/errors/404.view.php';
}
?>