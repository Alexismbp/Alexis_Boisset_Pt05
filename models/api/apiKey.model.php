<?php

/**
 * Funciones para la gestión de claves API
 */

/**
 * Valida si una clave API existe en la base de datos
 * @param PDO $conn Conexión a la base de datos
 * @param string $providedKey Clave API a validar
 * @return bool True si la clave es válida, False si no existe
 */
function validarApiKey($conn, $providedKey)
{
    $sql = "SELECT id FROM api_keys WHERE api_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$providedKey]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

/**
 * Genera o actualiza una clave API para un usuario específico
 * @param PDO $conn Conexión a la base de datos
 * @param int $userId ID del usuario para el que se genera la clave
 * @return string Nueva clave API generada
 */
function generateApiKey($conn, $userId)
{
    $newKey = bin2hex(random_bytes(16)); // genera 32 caràcters hex
    // Comprovar si ja existeix una API key per aquest usuari
    $sql = "SELECT id FROM api_keys WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Actualitzar la clau existent
        $sql = "UPDATE api_keys SET api_key = ?, created_at = CURRENT_TIMESTAMP WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newKey, $userId]);
    } else {
        // Inserir una nova API key
        $sql = "INSERT INTO api_keys (user_id, api_key) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId, $newKey]);
    }
    return $newKey;
}
