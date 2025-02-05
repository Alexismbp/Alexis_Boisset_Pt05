<?php
// Alexis Boisset

// Cargar autoload, variables de entorno y configuración inicial
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/models/env.php";
require_once __DIR__ . "/models/database/database.model.php";
require_once __DIR__ . "/controllers/session/session.controller.php";
require_once __DIR__ . "/core/Router.php";
require_once __DIR__ . '/controllers/middleware/AuthMiddleware.php';
require_once __DIR__ . '/controllers/auth/SocialAuthController.php';
require_once __DIR__ . '/controllers/api/MatchControllerApi.php';

// Iniciar la sesión si es necesario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ejecutar middleware de "remember me"
AuthMiddleware::handleRememberToken();

// DEBUGG
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// require_once __DIR__ . '/models/user/user.model.php';
// $_SERVER['REQUEST_METHOD'] = 'GET';
// $_SERVER['REQUEST_URI'] = 'http://localhost/Practiques/M07-Servidor/Alexis_Boisset_Pt05/qr-read';
// $email = 'a.boisset@sapalomera.cat';
// $userData = getUserData($email, $conn);
// SessionHelper::setSessionData([
//     'email' => $email,
//     'oauth_user' => $userData['is_oauth_user'],
//     'avatar' => $userData['avatar'] ?? 'default-avatar.webp',
//     'LAST_ACTIVITY' => time(),
//     'loggedin' => true,
//     'userid' => $userData['id'],
//     'username' => $userData['nom_usuari'],
//     'equip' => $userData['equip_favorit'],
//     'lliga' => getLeagueName($userData['equip_favorit'], $conn),
//     'success' => 'Usuari registrat correctament'
// ]);
