
<?php
require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\Github;
use Hybridauth\Provider\GitHub as HybridGitHub;

class SocialAuthController {
    private $provider;
    private $providerType;
    
    public function __construct(string $provider = 'google') {
        $this->providerType = $provider;
        $this->initializeProvider($provider);
    }

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

    private function handleGoogleCallback() {
        // ...existing Google callback code...
    }

    private function handleGitHubCallback() {
        $this->provider->authenticate();
        $userProfile = $this->provider->getUserProfile();

        $email = $userProfile->email;
        $name = $userProfile->displayName;

        $this->processAuthenticatedUser($email, $name, 'github');
    }

    private function processAuthenticatedUser($email, $name, $provider) {
        $conn = Database::getInstance();
        
        if (!userExists($email, $conn)) {
            registerUser($name, $email, null, 'pendiente', $conn, $provider, true);
            $needsPreferences = true;
        } else {
            $userData = getUserData($email, $conn);
            $needsPreferences = ($userData['equip_favorit'] === 'pendiente');
        }

        $lliga = !$needsPreferences ? getLeagueName($userData['equip_favorit'], $conn) : null;

        SessionHelper::setSessionData([
            'email' => $email,
            'username' => $name,
            'loggedin' => true,
            'oauth_user' => true,
            'needs_preferences' => $needsPreferences,
            'equip' => $needsPreferences ? null : $userData['equip_favorit'],
            'lliga' => $needsPreferences ? null : $lliga
        ]);

        header('Location: ' . BASE_URL . ($needsPreferences ? 'preferences' : ''));
        exit;
    }
}