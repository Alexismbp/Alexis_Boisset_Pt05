<?php
//Alexis Boisset

require_once __DIR__ . "/../env.php";

class Database
{
    private static $servername = DB_HOST;  // Host del servidor MySQL
    private static $dbname = DB_NAME;  // Nom de la base de dades
    private static $username = DB_USER;  // Nom d'usuari MySQL
    private static $password = DB_PASS;  // Contrasenya MySQL
    private static $charset = DB_CHARSET;  // Joc de caràcters
    private static $conn = null;  // Connexió a la base de dades
    private static $logfile = 'database.log'; // Archivo de logs

    // Constructor vacío
    public function __construct() {}

    public static function getInstance ()
    {
        if (self::$conn == null) {
            self::$conn = self::getInstance();
        }

        return self::$conn;
    }

    // Función para registrar errores
    private static function logError($message)
    {
        error_log($message . "\n", 3, self::$logfile);
    }

    public static function connect()
    {
        try {
            // Convertir el nom de la base de dades a minúscula per evitar problemes
            self::$dbname = strtolower(self::$dbname);

            // Generar el DSN->(Data Source Name)
            $dsn = "mysql:host=" . self::$servername . ";dbname=" . self::$dbname . ";charset=" . self::$charset;

            // Creem una nova connexió PDO
            $conn = new PDO($dsn, self::$username, self::$password);

            // Configurem PDO perquè llanci excepcions en cas d'error
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            self::logError("Error de connexió: " . $e->getMessage());
            die("Error de connexió: " . $e->getMessage());
        }
    }
}

$conn = Database::getInstance(); // Connexió a la base de dades
