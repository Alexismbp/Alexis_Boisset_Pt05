<?php
// Alexis Boisset

// FILE: index.php
$_SERVER['REQUEST_URI'] = "http://localhost/Practiques/M07-Servidor/Alexis_Boisset_Pt05/login";
// Incluir archivos necesarios

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['loggedin'] = false;
}

require_once "models/env.php";
require_once "models/database/database.model.php";
require_once "controllers/session/session.controller.php";


// Obtener la ruta solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Línea para funcionamiento en local
//$uri = '/' . ltrim(substr($uri, strlen(BASE_URL)), '/');

// Enrutamiento básico
if ($uri === '/' || $uri === '') {
    // Ruta principal
    include BASE_PATH . '/controllers/main.controller.php';
} elseif ($uri === '/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . '/controllers/auth/login.controller.php';
    } else {
        include BASE_PATH . '/views/auth/login/login.view.php';
    }
} elseif ($uri === '/logout') {
    include BASE_PATH . '/controllers/auth/logout.controller.php';
} elseif ($uri === '/register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . '/controllers/auth/register.controller.php';
    } else {
        include BASE_PATH . '/views/auth/register.view.php';
    }
} elseif ($uri === '/create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . '/controllers/crud/create.controller.php';
    } else {
        include BASE_PATH . '/views/crud/create.view.php';
    }
} elseif ($uri === '/delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . '/controllers/crud/delete.controller.php';
    } else {
        include BASE_PATH . '/views/crud/delete.view.php';
    }
} elseif ($uri === '/forgotpassword') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include BASE_PATH . '/controllers/auth/forgotpassword.controller.php';
    } else {
        include BASE_PATH . '/views/auth/forgotpassword.view.php';
    }
} else {
    // Enviar el encabezado 404
    header("HTTP/1.0 404 Not Found");
    exit();
}
?>