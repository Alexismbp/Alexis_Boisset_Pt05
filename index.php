<?php
// Alexis Boisset

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/models/env.php";
require_once __DIR__ . "/models/database/database.model.php";
require_once __DIR__ . "/controllers/session/session.controller.php";
require_once __DIR__ . "/core/Router.php";
require_once __DIR__ . '/controllers/middleware/AuthMiddleware.php';
require_once __DIR__ . '/controllers/auth/SocialAuthController.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

// Ejecutar middleware para cookie remember me
AuthMiddleware::handleRememberToken();

// Ruta para búsquedas
$router->get('/search', function () {
    require_once BASE_PATH . 'controllers/utils/search.controller.php'; 
    $conn = Database::getInstance();
    $searchController = new SearchController($conn);
    $term = $_GET['term'] ?? '';
    $results = $searchController->search($term);
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
});

// Definir rutas GET
$router->get('/', 'controllers/main.controller.php');
$router->get('/login', 'views/auth/login/login.view.php');
$router->get('/register', 'views/auth/register/register.view.php');
$router->get('/create', 'views/crud/create.view.php');
$router->get('/forgotpassword', 'views/auth/forgot/forgot-password.view.php');
$router->get('/changepassword', 'views/auth/change/change-password.view.php');
$router->get('/resetpassword', 'views/auth/reset/reset-password.view.php');
$router->get('/profile', 'views/auth/profile/profile.view.php');
$router->get('/preferences', 'views/auth/preferences/preferences.view.php');
$router->get('/merge-accounts', 'views/auth/merge/merge-accounts.view.php');

// Rutas para partidos
$router->get('/create-match', 'views/crud/create/match-create.view.php');
$router->get('/edit-match/{id}', function () use ($router) {
    include BASE_PATH . 'views/crud/edit/match-edit.view.php';
});

$router->get('/view-match/{id}', function () use ($router) {
    include BASE_PATH . 'controllers/crud/view-match.controller.php';
});

$router->post('/save-match', 'controllers/crud/save-match.controller.php');

// Rutas OAuth
$router->get('/oauth/{provider}', function () use ($router) {
    $provider = $router->getParam('provider');
    $auth = new SocialAuthController($provider);
    $auth->redirectToProvider();
});

$router->get('/oauth/{provider}/callback', function () use ($router) {
    $provider = $router->getParam('provider');
    $auth = new SocialAuthController($provider);
    $auth->handleCallback();
});

// Definir rutas GET (Admin)
$router->get('/manage-users', 'controllers/admin/manage-users.controller.php');

// Definir rutas POST (Admin)
$router->post('/delete-user', 'controllers/admin/manage-users.controller.php');

// Definir rutas POST
$router->post('/login', 'controllers/auth/login.controller.php');
$router->post('/register', 'controllers/auth/register.controller.php');
$router->post('/create', 'controllers/crud/create.controller.php');
$router->post('/delete-match', 'controllers/crud/delete.controller.php');
$router->post('/forgotpassword', 'controllers/auth/email-password.controller.php');
$router->post('/changepassword', 'controllers/auth/change-password.controller.php');
$router->post('/resetpassword', 'controllers/auth/reset-password.controller.php');
$router->post('/save-profile', 'controllers/auth/profile.controller.php');
$router->post('/save-preferences', 'controllers/auth/save-preferences.controller.php');
$router->post('/merge-accounts', 'controllers/auth/merge-accounts.controller.php');
$router->get('/logout', 'controllers/auth/logout.controller.php');


// Obtener y procesar la URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$uri = "/" . ltrim(substr($uri, strlen($basePath)), '/');

try {
    $result = $router->dispatch($uri);
    
    if ($result instanceof Closure) {
        $result();
    } else {
        include BASE_PATH . $result;
    }
} catch (Exception $e) {
    if ($e->getMessage() === 'Route not found') {
        http_response_code(404);
        include BASE_PATH . 'views/errors/404.view.php';
    } else {
        // Manejar otras excepciones
        http_response_code(500);
        echo 'Error Interno del Servidor';
    }
}
?>
