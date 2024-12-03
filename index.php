<?php
// Desactivar la visualización de errores en producción
/* ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/models/env.php";
require_once __DIR__ . "/models/database/database.model.php";
require_once __DIR__ . "/controllers/session/session.controller.php";
require_once __DIR__ . "/core/Router.php";
require_once __DIR__ . '/controllers/middleware/AuthMiddleware.php';
require_once __DIR__ . '/controllers/auth/SocialAuthController.php'; // Cambiar nombre del archivo

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DEBUG
/* $_SESSION['loggedin'] = false;
$_SERVER['REQUEST_URI'] = "http://localhost/Practiques/M07-Servidor/Alexis_Boisset_Pt05/match/44";
$_SERVER['REQUEST_METHOD'] = "POST"; */
/* $_SESSION['email'] = 'a.boisset@sapalomera.cat'; */

/* $userData = getUserData($_SESSION['email'], $conn); */

/* SessionHelper::setSessionData([
    'email' => $_SESSION['email'],
    'avatar' => $userData['avatar'],
    'LAST_ACTIVITY' => time(),
    'loggedin' => true,
    'userid' => $userData['id'],
    'username' => $userData['nom_usuari'],
    'equip' => $userData['equip_favorit'],
    'lliga' => getLeagueName($userData['equip_favorit'], $conn)
]); */

/* $_POST['username'] = 'Marc Pérez';
$_POST['equip'] = 'Real Madrid'; */
/* $_POST['current_password'] = 'Admin123';
$_POST['new_password'] = 'Admin123!';
$_POST['confirm_password'] = 'Admin123!'; */


/*  $_POST['username'] = 'Alexis Marc'; */
/* $_POST['equip'] = 'Atlético de Madrid';
$_SERVER['REQUEST_METHOD'] = 'POST'; */
/* $_COOKIE['remember_token'] = '412fb53ea4c764e0f7ecd392554abf1959b9644978ba366321ebf21e0af5f741'; */



$router = new Router();

// Ejecutar middleware de autenticación
AuthMiddleware::handleRememberToken();

// Añadir la ruta para búsquedas
$router->get('/search', function() {
    require_once __DIR__ . '/controllers/utils/search.controller.php'; // Asegurar la ruta correcta
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
// Eliminar referencia a delete.view.php si existe
// Por ejemplo, si hay una ruta GET para eliminación, eliminarla
// $router->get('/delete', 'views/crud/delete/delete.view.php');
$router->get('/forgotpassword', 'views/auth/forgot/forgot-password.view.php');
$router->get('/changepassword', 'views/auth/change/change-password.view.php');
$router->get('/resetpassword', 'views/auth/reset/reset-password.view.php');
$router->get('/profile', 'views/auth/profile/profile.view.php');

// Rutas para partidos
$router->get('/create-match', 'views/crud/create/match-create.view.php');
$router->get('/edit-match/{id}', function() use ($router) {
    include BASE_PATH . 'views/crud/edit/match-edit.view.php';
});
$router->get('/view-match/{id}', function() use ($router) {
    include BASE_PATH . 'controllers/crud/view-match.controller.php';
});
$router->post('/save-match', 'controllers/crud/save-match.controller.php');

// Rutas OAuth
$router->get('/oauth/{provider}', function() use ($router) {
    $provider = $router->getParam('provider');
    $auth = new SocialAuthController($provider);
    $auth->redirectToProvider();
});

$router->get('/oauth/{provider}/callback', function() use ($router) {
    $provider = $router->getParam('provider');
    $auth = new SocialAuthController($provider);
    $auth->handleCallback();
});

// Definir rutas GET
$router->get('/manage-users', 'controllers/admin/manage-users.controller.php');

// Definir rutas POST
$router->post('/delete-user', 'controllers/admin/manage-users.controller.php');

// Definir rutas POST
$router->post('/login', 'controllers/auth/login.controller.php');
$router->post('/register', 'controllers/auth/register.controller.php');
$router->post('/create', 'controllers/crud/create.controller.php');
$router->post('/delete-match', 'controllers/crud/delete.controller.php'); // Asegurar esta ruta
$router->post('/forgotpassword', 'controllers/auth/email-password.controller.php');
$router->post('/changepassword', 'controllers/auth/change-password.controller.php');
$router->post('/resetpassword', 'controllers/auth/reset-password.controller.php');
$router->post('/save-profile', 'controllers/auth/profile.controller.php');
$router->post('/save-preferences', 'controllers/auth/save-preferences.controller.php');
$router->post('/merge-accounts', 'controllers/auth/merge-accounts.controller.php');
$router->get('/logout', 'controllers/auth/logout.controller.php');

// Rutas para artículos
$router->get('/edit-article/{id}', 'views/crud/edit/edit-article.view.php');
$router->post('/save-article', 'controllers/crud/save-article.controller.php');

// Añadir nuevas rutas
$router->get('/preferences', 'views/auth/preferences/preferences.view.php');
$router->post('/save-preferences', 'controllers/auth/save-preferences.controller.php');

// Añadir rutas para la fusión de cuentas
$router->get('/merge-accounts', 'views/auth/merge/merge-accounts.view.php');
$router->post('/merge-accounts', 'controllers/auth/merge-accounts.controller.php');

// Obtener y procesar la URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$uri = "/" . ltrim(substr($uri, strlen($basePath)), '/');

// try {
    $result = $router->dispatch($uri);
    
    if ($result instanceof Closure) {
        $result();
    } else {
        include BASE_PATH . $result;
    }
/* } catch (Exception $e) {
    // Si es una ruta de archivo y no se encuentra, devolver 404
    if (strpos($uri, '/uploads/') === 0 || 
        strpos($uri, '/assets/') === 0 || 
        strpos($uri, '/views/') === 0) {
        http_response_code(404);
        exit;
    }
    
    http_response_code(404);
    include BASE_PATH . 'views/errors/404.view.php';
} */
?>
