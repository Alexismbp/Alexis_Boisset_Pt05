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
    private static $logfile = 'database.log'; // Archivo de logs (DEPRECATED)

    // Constructor vacío
    public function __construct() {}

    public static function getInstance()
    {
        if (self::$conn == null) {
            try {
                $dsn = "mysql:host=" . self::$servername . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
                self::$conn = new PDO($dsn, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                self::logError($e->getMessage());
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }

        return self::$conn;
    }

    // Función para registrar errores
    private static function logError($message)
    {
        file_put_contents(self::$logfile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }
}

$conn = Database::getInstance(); // Connexió a la base de dades
