<?php

// Constantes de configuración de base de datos
define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_CHARSET', '');

// Constantes de rutas
define('BASE_PATH', dirname(__DIR__) . '/');
define('BASE_URL', '');

// Constantes de reCAPTCHA v2
define('SITE_KEY', '');
define('SECRET_KEY', '');

// Constantes de OAuth Google
define('GOOGLE_CLIENT_ID', '');
define('GOOGLE_CLIENT_SECRET', '');
define('GOOGLE_REDIRECT_URI', BASE_URL . 'oauth/google/callback');

// Constantes de OAuth GitHub
define('GITHUB_CLIENT_ID', '');
define('GITHUB_CLIENT_SECRET', '');
define('GITHUB_REDIRECT_URI', BASE_URL . 'oauth/github/callback');

// PHPMailer
?>