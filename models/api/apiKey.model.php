<?php
function validarApiKey($conn, $providedKey)
{
    $sql = "SELECT id FROM api_keys WHERE api_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$providedKey]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

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
