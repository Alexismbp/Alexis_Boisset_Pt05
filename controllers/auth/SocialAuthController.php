<?php
/**
 * Controlador para gestionar la autenticación social mediante OAuth2
 * 
 * Esta clase maneja la autenticación de usuarios a través de proveedores sociales
 * como Google y GitHub utilizando el protocolo OAuth2 o HybridAuth.
 */

require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

use League\OAuth2\Client\Provider\Google;
use Hybridauth\Provider\GitHub as HybridGitHub;

class SocialAuthController {
    /** @var Google|HybridGitHub El proveedor de autenticación */
    private $provider;
    
    /** @var string El tipo de proveedor ('google'|'github') */
    private $providerType;
    
    /**
     * Constructor del controlador
     * 
     * @param string $provider Tipo de proveedor de autenticación ('google' por defecto)
     */
    public function __construct(string $provider = 'google') {
        $this->providerType = $provider;
        $this->initializeProvider($provider);
    }

    /**
     * Inicializa el proveedor de autenticación según el tipo especificado
     * 
     * @param string $provider Tipo de proveedor ('google'|'github')
     * @throws Exception Si el proveedor no está soportado
     */
    private function initializeProvider(string $provider) {
        switch ($provider) {
            case 'google':
                $this->provider = new Google([
                    'clientId' => GOOGLE_CLIENT_ID,
                    'clientSecret' => GOOGLE_CLIENT_SECRET,
                    'redirectUri' => GOOGLE_REDIRECT_URI,
                ]);
                break;
            case 'github':
                $this->provider = new HybridGitHub([
                    'callback' => GITHUB_REDIRECT_URI,
                    'keys' => [
                        'id' => GITHUB_CLIENT_ID,
                        'secret' => GITHUB_CLIENT_SECRET
                    ]
                ]);
                break;
            default:
                throw new Exception('Proveedor no soportado');
        }
    }

    /**
     * Redirige al usuario al proveedor de autenticación
     * 
     * @throws Exception Si hay un error en el proceso de autenticación
     */
    public function redirectToProvider() {
        try {
            if ($this->providerType === 'google') {
                $authUrl = $this->provider->getAuthorizationUrl([
                    'scope' => ['email', 'profile']
                ]);
                $_SESSION['oauth2state'] = $this->provider->getState();
            } else {
                $this->provider->authenticate();
                $authUrl = $this->provider->getAuthorizationUrl();
            }
            
            header('Location: ' . $authUrl);
            exit;
        } catch (Exception $e) {
            $_SESSION['failure'] = "Error de autenticación: " . $e->getMessage();
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    /**
     * Maneja la respuesta del proveedor de autenticación
     * 
     * @throws Exception Si hay un error en el proceso de callback
     */
    public function handleCallback() {
        try {
            if ($this->providerType === 'google') {
                $this->handleGoogleCallback();
            } else {
                $this->handleGitHubCallback();
            }
        } catch (Exception $e) {
            $_SESSION['failure'] = "Error de autenticación: " . $e->getMessage();
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    /**
     * Procesa el callback específico de Google
     * 
     * @throws Exception Si no se recibe el código o hay error en la autenticación
     */
    private function handleGoogleCallback() {
        if (!isset($_GET['code'])) {
            throw new Exception('No se recibió el código de autorización');
        }

        if (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            throw new Exception('Invalid state parameter');
        }

        try {
            // Obtener el token de acceso
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Obtener datos del usuario
            /** @var \League\OAuth2\Client\Provider\GoogleUser $user */
            $user = $this->provider->getResourceOwner($token);
            
            $email = $user->getEmail();
            $name = $user->getName();

            $this->processAuthenticatedUser($email, $name, 'google');

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            error_log("Error en OAuth: " . $e->getMessage());
            throw new Exception('Error al obtener datos del usuario: ' . $e->getMessage());
        }
    }

    /**
     * Procesa el callback específico de GitHub
     */
    private function handleGitHubCallback() {
        $this->provider->authenticate();
        $userProfile = $this->provider->getUserProfile();

        $email = $userProfile->email;
        $name = $userProfile->displayName;

        $this->processAuthenticatedUser($email, $name, 'github');
    }

    /**
     * Procesa los datos del usuario autenticado y gestiona su sesión
     * 
     * @param string $email Email del usuario
     * @param string $name Nombre del usuario
     * @param string $provider Tipo de proveedor utilizado
     */
    private function processAuthenticatedUser($email, $name, $provider) {
        $conn = Database::getInstance();
        
        if (!userExists($email, $conn)) {
            registerUser($name, $email, null, 'pendiente', $conn, $provider, true);
            $needsPreferences = true;
            $userData = ['equip_favorit' => 'pendiente']; // Añadir esta línea
        } else {
            $userData = getUserData($email, $conn);
            
            // Si la cuenta existe pero no es OAuth, preguntar por fusión
            if (!$userData['is_oauth_user']) {
                $_SESSION['temp_email'] = $email;
                $_SESSION['temp_name'] = $name;
                $_SESSION['temp_provider'] = $provider;
                header('Location: ' . BASE_URL . 'merge-accounts');
                exit;
            }
            
            $needsPreferences = ($userData['equip_favorit'] === 'pendiente');
        }

        $lliga = !$needsPreferences ? getLeagueName($userData['equip_favorit'], $conn) : null;

        SessionHelper::setSessionData([
            'email' => $email,
            'userid' => $userData['id'],
            'username' => $userData['nom_usuari'],
            'loggedin' => true,
            'oauth_user' => 1, // Asegurar que sea int 1
            'needs_preferences' => $needsPreferences,
            'equip' => $needsPreferences ? null : $userData['equip_favorit'],
            'lliga' => $needsPreferences ? null : $lliga,
            'avatar' => $userData['avatar'] 
        ]);

        header('Location: ' . BASE_URL . ($needsPreferences ? 'preferences' : ''));
        exit;
    }
}