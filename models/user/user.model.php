<?php
/**
 * Modelo de usuarios - Gestiona las operaciones relacionadas con usuarios
 * @author Alexis Boisset
 */

/**
 * Verifica si un usuario existe por su email
 * @param string $email
 * @param PDO $conn
 * @return bool True si el usuario existe, false si no
 */
function userExists(string $email, PDO $conn): bool
{
    $query = $conn->prepare("SELECT COUNT(*) FROM usuaris WHERE correu_electronic = :email");
    $query->bindParam(':email', $email);
    $query->execute();
    
    return $query->fetchColumn() > 0;
}

/**
 * Registra un nuevo usuario en el sistema
 * @param string $username
 * @param string $email
 * @param string|null $password
 * @param string|null $equipFavorit
 * @param PDO $conn
 * @param string $oauthProvider
 * @param bool $isOAuth
 * @return bool
 */
function registerUser(
    string $username, 
    string $email, 
    ?string $password, 
    ?string $equipFavorit, 
    PDO $conn, 
    string $oauthProvider = '', // Mover parámetro requerido antes del opcional
    bool $isOAuth = false
): bool {
    if (userExists($email, $conn)) {
        return false;
    }

    $nextId = ultimaIdDisponible($conn); // Cambiado de getNextId a ultimaIdDisponible
    
    $query = $conn->prepare("INSERT INTO usuaris (id, nom_usuari, correu_electronic, contrasenya, equip_favorit, is_oauth_user, oauth_provider) 
                            VALUES (:id, :username, :email, :password, :equip, :oauth, :oauthProvider)");
    
    $params = [
        ':id' => $nextId,
        ':username' => $username,
        ':email' => $email,
        ':password' => $isOAuth ? null : $password,
        ':equip' => $equipFavorit ?? 'No especificado', // Valor por defecto ya que equip_favorit no permite NULL
        ':oauth' => $isOAuth ? 1 : 0,
        ':oauthProvider' => $oauthProvider
    ];
    
    return $query->execute($params);
}

/**
 * Obtiene los datos de un usuario por su email
 * @param string $email
 * @param PDO $conn
 * @return array|false
 */
function getUserData(string $email, PDO $conn)
{
    if (!userExists($email, $conn)) {
        return false;
    }

    $sql = $conn->prepare("SELECT id, nom_usuari, equip_favorit, contrasenya 
                          FROM usuaris 
                          WHERE correu_electronic = :email");
    $sql->bindParam(':email', $email);
    $sql->execute();
    
    return $sql->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtiene el nombre de la liga del equipo favorito
 * @param string $equipFavorit
 * @param PDO $conn
 * @return string|false
 */
function getLeagueName(string $equipFavorit, PDO $conn)
{
    $query = $conn->prepare("SELECT l.nom 
                           FROM lligues l
                           INNER JOIN equips e ON e.lliga_id = l.id 
                           WHERE e.nom = :equipFavorit");
    $query->bindParam(':equipFavorit', $equipFavorit);
    $query->execute();
    
    return $query->fetch(PDO::FETCH_COLUMN);
}

/**
 * Encuentra el primer ID disponible en la tabla de usuarios
 * @param PDO $conn
 * @return int
 */
function ultimaIdDisponible(PDO $conn): int
{
    $query = $conn->prepare("SELECT id FROM usuaris ORDER BY id");
    $query->execute();
    $ids = $query->fetchAll(PDO::FETCH_COLUMN, 0);
    
    $contador = 1;
    foreach ($ids as $id) {
        if ($contador != $id) {
            return $contador;
        }
        $contador++;
    }
    
    return $contador;
}

/**
 * Almacena el token de recuperación de contraseña
 * @param string $email
 * @param string $token
 * @param PDO $conn
 * @return bool
 */
function storeToken(string $email, string $token, PDO $conn): bool
{
    try {
        $expiry = date('Y-m-d H:i:s', strtotime('+2 hours'));
        $sql = "UPDATE usuaris 
                SET reset_token_hash = :token, 
                    reset_token_expires_at = :expiry 
                WHERE correu_electronic = :email";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiry);
        $stmt->bindParam(':email', $email);
        
        return $stmt->execute() && $stmt->rowCount() > 0;
        
    } catch (PDOException $e) {
        error_log("Error en storeToken: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica la contraseña actual del usuario
 * @param string $email
 * @param string $password
 * @param PDO $conn
 * @return bool
 */
function verifyCurrentPassword(string $email, string $password, PDO $conn): bool {
    $query = $conn->prepare("SELECT contrasenya FROM usuaris WHERE correu_electronic = :email");
    $query->bindParam(':email', $email);
    $query->execute();
    
    $hash = $query->fetchColumn();
    return password_verify($password, $hash);
}

/**
 * Actualiza la contraseña del usuario
 * @param string $email
 * @param string $hashedPassword
 * @param PDO $conn
 * @return bool
 */
function updatePassword(string $email, string $hashedPassword, PDO $conn): bool {
    $query = $conn->prepare("UPDATE usuaris SET contrasenya = :password WHERE correu_electronic = :email");
    $query->bindParam(':password', $hashedPassword);
    $query->bindParam(':email', $email);
    return $query->execute();
}

/**
 * Verifica el token de recuperación de contraseña
 * @param string $token
 * @param PDO $conn
 * @return string|null
 */
function verifyToken(string $token, PDO $conn): ?string {
    $query = $conn->prepare("SELECT correu_electronic FROM usuaris 
                           WHERE reset_token_hash = :token 
                           AND reset_token_expires_at > NOW()");
    $query->bindParam(':token', $token);
    $query->execute();
    return $query->fetchColumn();
}

/**
 * Almacena el token de "recordarme"
 * @param int $userId
 * @param string $token
 * @param int $expiry
 * @param PDO $conn
 * @return bool
 */
function storeRememberToken(int $userId, string $token, int $expiry, PDO $conn): bool {
    try {
        $sql = "UPDATE usuaris 
                SET remember_token = :token,
                    remember_token_expires = :expiry
                WHERE id = :user_id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':token' => $token,
            ':expiry' => date('Y-m-d H:i:s', $expiry),
            ':user_id' => $userId
        ]);
    } catch (PDOException $e) {
        error_log("Error storing remember token: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtiene un usuario por su token de "recordarme"
 * @param string $token
 * @param PDO $conn
 * @return array|false
 */
function getUserByRememberToken(string $token, PDO $conn) {
    try {
        $sql = "SELECT * FROM usuaris 
                WHERE remember_token = :token 
                AND remember_token_expires > NOW()";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([':token' => $token]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting user by remember token: " . $e->getMessage());
        return false;
    }
}

/**
 * Cleans up expired tokens in the 'usuaris' table.
 *
 * This function sets the 'remember_token' and 'remember_token_expires' fields to NULL
 * for all records where 'remember_token_expires' is less than the current date and time.
 * It also sets the 'reset_token_hash' and 'reset_token_expires_at' fields to NULL
 * for the same records.
 *
 * @param PDO $conn The PDO connection object to the database.
 * @return bool Returns true if the operation was successful, false otherwise.
 */
function cleanupExpiredTokens(PDO $conn): bool {
    try {
        // Primera consulta para tokens de remember me
        $sql = "UPDATE usuaris 
                SET remember_token = NULL,
                    remember_token_expires = NULL 
                WHERE remember_token_expires < NOW()";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Segunda consulta para tokens de reset password
        $sql = "UPDATE usuaris 
                SET reset_token_hash = NULL,
                    reset_token_expires_at = NULL 
                WHERE reset_token_expires_at < NOW()";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Error limpiando tokens: " . $e->getMessage());
        return false;
    }
}

/**
 * Actualiza las preferencias del usuario
 * @param string $email
 * @param string $equip
 * @param PDO $conn
 * @return bool
 */
function updateUserPreferences(string $email, string $equip, PDO $conn): bool {
    $sql = "UPDATE usuaris SET equip_favorit = :equip WHERE correu_electronic = :email";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':equip' => $equip,
        ':email' => $email
    ]);
}