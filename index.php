<?php
require_once __DIR__ . '/vendor/autoload.php';
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

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
/* $_SESSION['loggedin'] = true; */
$_SERVER['REQUEST_URI'] = "http://localhost/Practiques/M07-Servidor/Alexis_Boisset_Pt05/profile";
$_SERVER['REQUEST_METHOD'] = "GET"; 
$_SESSION['loggedin'] = true;
/* $_POST['username'] = 'Marc Pérez';
$_POST['equip'] = 'Real Madrid'; */
/* $_POST['current_password'] = 'Admin123';
$_POST['new_password'] = 'Admin123!';
$_POST['confirm_password'] = 'Admin123!'; */


/*  $_POST['username'] = 'Alexis Marc'; */
/* $_SESSION['email'] = 'a.boisset@sapalomera.cat'; */
/* $_POST['equip'] = 'Atlético de Madrid';
$_SERVER['REQUEST_METHOD'] = 'POST'; */
/* $_COOKIE['remember_token'] = '412fb53ea4c764e0f7ecd392554abf1959b9644978ba366321ebf21e0af5f741'; */



$router = new Router();

// Ejecutar middleware de autenticación
AuthMiddleware::handleRememberToken();

// Definir rutas GET
$router->get('/', 'controllers/main.controller.php');
$router->get('/login', 'views/auth/login/login.view.php');
$router->get('/register', 'views/auth/register/register.view.php');
$router->get('/create', 'views/crud/create.view.php');
$router->get('/delete', 'views/crud/delete.view.php');
$router->get('/forgotpassword', 'views/auth/forgot/forgot-password.view.php');
$router->get('/changepassword', 'views/auth/change/change-password.view.php');
$router->get('/resetpassword', 'views/auth/reset/reset-password.view.php');
$router->get('/profile', 'views/auth/profile/profile.view.php');

// Rutas para partidos
$router->get('/create-match', 'views/crud/create/create-match.view.php');
$router->get('/edit-match/{id}', 'views/crud/edit/edit-match.view.php');
$router->post('/update-match', 'controllers/crud/update-match.controller.php');
$router->post('/save-match', 'controllers/crud/save-match.controller.php');
$router->post('/delete-match', 'controllers/crud/delete-match.controller.php');

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

// Definir rutas POST
$router->post('/login', 'controllers/auth/login.controller.php');
$router->post('/register', 'controllers/auth/register.controller.php');
$router->post('/create', 'controllers/crud/create.controller.php');
$router->post('/delete', 'controllers/crud/delete.controller.php');
$router->post('/forgotpassword', 'controllers/auth/email-password.controller.php');
$router->post('/changepassword', 'controllers/auth/change-password.controller.php');
$router->post('/resetpassword', 'controllers/auth/reset-password.controller.php');
$router->post('/save-profile', 'controllers/auth/profile.controller.php');
$router->get('/logout', 'controllers/auth/logout.controller.php');



// Rutas para artículos DEPRACATED
$router->post('/save-article', 'controllers/crud/save-article.controller.php');
$router->get('/edit-article/{id}', 'views/crud/edit/edit-article.view.php');
$router->post('/update-article', 'controllers/crud/update-article.controller.php');
$router->post('/delete-article', 'controllers/crud/delete-article.controller.php');

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
// } catch (Exception $e) {
//    http_response_code(404);
//    include BASE_PATH . 'views/errors/404.view.php';
// }
