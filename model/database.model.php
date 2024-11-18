<?php
//Alexis Boisset

/* ABANS CLASS DATABASE */

$servername = "bbdd.alexisboisset.cat";  // Host del servidor MySQL
$dbname = "ddb237139";  // Nom de la base de dades
$username = "ddb237139";  // Nom d'usuari MySQL
$password = "rEmhyn-johwiz-9citzy";  // Contrasenya MySQL

// Funció per connectar a BD
function connect()
{
    try {
        global $servername, $dbname, $username, $password;

        // Convertir el nom de la base de dades a minúscula per evitar problemes
        $dbname = strtolower($dbname);

        // Generar el DSN->(Data Source Name)
        $dsn = "mysql:host=" . $servername . ";dbname=" . $dbname;

        // Creem una nova connexió PDO
        $conn = new PDO($dsn, $username, $password);

        // Configurem PDO perquè llanci excepcions en cas d'error
        #$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch (PDOException $e) {
        die("Error de connexió: " . $e->getMessage());
        return null;
    }
}
