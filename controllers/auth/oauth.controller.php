<?php
require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OAuthController {
    private $provider;
    
    public function __construct() {
        $this->provider = new Google([
            'clientId' => GOOGLE_CLIENT_ID,
            'clientSecret' => GOOGLE_CLIENT_SECRET,
            'redirectUri' => GOOGLE_REDIRECT_URI, 
        ]);
    }

    public function redirectToProvider() {
        try {
            $authUrl = $this->provider->getAuthorizationUrl([
                'scope' => ['email', 'profile']
            ]);
            
            $_SESSION['oauth2state'] = $this->provider->getState();
            error_log("Redirecting to Google OAuth: " . $authUrl);
            header('Location: ' . $authUrl);
            exit;
        } catch (Exception $e) {
            error_log("Error in redirectToProvider: " . $e->getMessage());
            $_SESSION['failure'] = "Error al conectar con Google: " . $e->getMessage();
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function handleCallback() {
        if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            throw new Exception('Invalid state');
        }

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            $user = $this->provider->getResourceOwner($token);
            
            // Obtener datos del usuario
            $email = $user->getEmail();
            $name = $user->getName();

            $conn = Database::getInstance();
            
            // Verificar si el usuario existe
            if (!userExists($email, $conn)) {
                // Registrar nuevo usuario con valores temporales
                registerUser(
                    $name, 
                    $email, 
                    null, 
                    'pendiente', 
                    $conn, 
                    'google', // Ahora va antes de isOAuth
                    true
                );
            }

            // Establecer sesión
            SessionHelper::setSessionData([
                'email' => $email,
                'username' => $name,
                'loggedin' => true,
                'oauth_user' => true,
                'needs_preferences' => true // Nueva bandera
            ]);

            // Redirigir a la página de preferencias si es necesario
            header('Location: ' . BASE_URL . 'preferences');
            exit;

        } catch (Exception $e) {
            $_SESSION['failure'] = "Error en la autenticación OAuth: " . $e->getMessage();
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}