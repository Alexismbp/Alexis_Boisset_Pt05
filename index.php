<?php
// Alexis Boisset

$router = new Router();

// Obtener y procesar la URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$uri = "/" . ltrim(substr($uri, strlen($basePath)), '/');

// Registrar rutas específicas para API únicamente si la URI comienza con /api
if (strpos($uri, '/api') === 0) {
    $matchControllerApi = new MatchControllerApi(Database::getInstance());
    $router->get('/api/partidos', function () use ($matchControllerApi) {
        $matchControllerApi->apiGetPartidos();
    });
    $router->get('/api/partidos/{id}', function () use ($matchControllerApi) {
        $matchControllerApi->apiGetPartido($_GET['id']);
    });
    $router->post('/api/partidos', function () use ($matchControllerApi) {
        $matchControllerApi->apiCreatePartido();
    });
    $router->put('/api/partidos/{id}', function () use ($matchControllerApi) {
        $matchControllerApi->apiUpdatePartido($_GET['id']);
    });
    $router->delete('/api/partidos/{id}', function () use ($matchControllerApi) {
        $matchControllerApi->apiDeletePartido($_GET['id']);
    });
}

// Registrar rutas no-API
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

// Ruta para la lista de artículos compartidos
$router->get('/shared-articles', 'controllers/shared/list-shared-articles.controller.php');

// Ruta para AJAX de artículos compartidos
$router->get('/ajax-shared-articles', 'ajax/list-shared-articles.ajax.php');

// Ruta para visualizar/editar artículo compartido
$router->get('/shared/{token}', function () use ($router) {
    $token = $router->getParam('token');
    include BASE_PATH . 'controllers/shared/view-shared-article.controller.php';
});

// También agregar la ruta POST para el mismo endpoint
$router->post('/shared/{token}', function () use ($router) {
    $token = $router->getParam('token');
    include BASE_PATH . 'controllers/shared/view-shared-article.controller.php';
});

// Rutas para compartir artículos
$router->get('/share/{token}', function () use ($router) {
    $token = $router->getParam('token');
    require BASE_PATH . 'controllers/shared/shared.controller.php';
});

$router->post('/share-article', 'controllers/shared/register-shared-article.controller.php');

$router->post('/share',  'controllers/utils/qr.php');
$router->post('/qr-read', 'controllers/utils/qr-read.php');
$router->get('/qr-read', 'views/qr/qr-read.view.php');

// Definir rutas GET (Admin)
$router->get('/manage-users', 'controllers/admin/manage-users.controller.php');

// Definir rutas POST (Admin)
$router->post('/delete-user', 'controllers/admin/manage-users.controller.php');

// Definir rutas GET para equipos
$router->get('/teams', 'controllers/teams/teams-list.controller.php');
$router->get('/team/{id}', 'views/teams/players-list.view.php');

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
