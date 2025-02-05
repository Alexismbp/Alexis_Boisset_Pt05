<?php
// Alexis Boisset

/**
 * Inserta un nuevo partido en la base de datos
 * @param PDO $conn Conexión a la base de datos
 * @param int $equipo_local_id ID del equipo local
 * @param int $equipo_visitante_id ID del equipo visitante
 * @param int $liga_id ID de la liga
 * @param string $fecha Fecha del partido
 * @param int|null $goles_local Goles del equipo local
 * @param int|null $goles_visitante Goles del equipo visitante
 * @return int ID del partido insertado
 * @throws PDOException Si hay error en la inserción
 */
function insertPartido($conn, $equipo_local_id, $equipo_visitante_id, $liga_id, $fecha, $goles_local, $goles_visitante)
{
    $jugado = (!is_null($goles_local) && !is_null($goles_visitante)) ? 1 : 0;
    $sql = "INSERT INTO partits (equip_local_id, equip_visitant_id, liga_id, data, gols_local, gols_visitant, jugat) 
            VALUES (:equipo_local_id, :equipo_visitante_id, :liga_id, :fecha, :goles_local, :goles_visitante, :jugado)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':equipo_local_id', $equipo_local_id);
        $stmt->bindParam(':equipo_visitante_id', $equipo_visitante_id);
        $stmt->bindParam(':liga_id', $liga_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':goles_local', $goles_local);
        $stmt->bindParam(':goles_visitante', $goles_visitante);
        $stmt->bindParam(':jugado', $jugado);

        $stmt->execute();
        return $conn->lastInsertId(); // Retorna el ID del partido insertado
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * Actualiza los datos de un partido existente
 * @param PDO $conn Conexión a la base de datos
 * @param int $id ID del partido a actualizar
 * @param int $equipo_local_id ID del equipo local
 * @param int $equipo_visitante_id ID del equipo visitante
 * @param string $fecha Nueva fecha del partido
 * @param int|null $goles_local Goles del equipo local
 * @param int|null $goles_visitante Goles del equipo visitante
 * @param bool $jugado Estado del partido
 * @return PDOStatement Objeto statement con la consulta preparada
 */
function updatePartido($conn, $id, $equipo_local_id, $equipo_visitante_id, $fecha, $goles_local, $goles_visitante, $jugado)
{
    $sql = "UPDATE partits 
            SET equip_local_id = :equipo_local_id, 
                equip_visitant_id = :equipo_visitante_id, 
                data = :fecha, 
                gols_local = :goles_local, 
                gols_visitant = :goles_visitante, 
                jugat = :jugado 
            WHERE id = :id";

    $stmt = $conn->prepare($sql);

    // Vincular todos los parámetros
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':equipo_local_id', $equipo_local_id);
    $stmt->bindParam(':equipo_visitante_id', $equipo_visitante_id);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':goles_local', $goles_local);
    $stmt->bindParam(':goles_visitante', $goles_visitante);
    $stmt->bindParam(':jugado', $jugado);

    return $stmt;
}

/**
 * Consulta los datos de un partido específico
 * @param PDO $conn Conexión a la base de datos
 * @param int $id ID del partido a consultar
 * @return array|false Datos del partido o false si no existe
 */
function consultarPartido($conn, $id)
{
    try {
        $sql = "SELECT * FROM partits WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en consultarPartido: " . $e->getMessage());
        return false;
    }
}

/**
 * Elimina un partido de la base de datos
 * @param PDO $conn Conexión a la base de datos
 * @param int $partit_id ID del partido a eliminar
 * @return bool True si se eliminó correctamente
 */
function deletePartit($conn, $partit_id)
{
    $sql = "DELETE FROM partits WHERE id = :partit_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':partit_id', $partit_id, PDO::PARAM_INT);
    return $stmt->execute();
}

/**
 * Guarda una predicción de usuario para un partido
 * @param PDO $conn Conexión a la base de datos
 * @param int $partit_id ID del partido
 * @param int $usuari_id ID del usuario
 * @param int $gols_local Goles predichos para el equipo local
 * @param int $gols_visitant Goles predichos para el equipo visitante
 * @return bool True si se guardó correctamente
 */
function guardarPrediccio($conn, $partit_id, $usuari_id, $gols_local, $gols_visitant)
{
    $stmt = $conn->prepare("INSERT INTO prediccions (partit_id, usuari_id, gols_local, gols_visitant) VALUES (:partit_id, :usuari_id, :gols_local, :gols_visitant)");

    // Vincular params
    $stmt->bindParam(':partit_id', $partit_id);
    $stmt->bindParam(':usuari_id', $usuari_id);
    $stmt->bindParam(':gols_local', $gols_local);
    $stmt->bindParam(':gols_visitant', $gols_visitant);

    // Executar i tornar resultat
    return $stmt->execute();
}

/**
 * Obtiene el nombre de un equipo por su ID
 * @param PDO $conn Conexión a la base de datos
 * @param int $id ID del equipo
 * @return string Nombre del equipo
 */
function getTeamName($conn, $id)
{
    $stmt = $conn->prepare("SELECT nom FROM equips WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

/**
 * Obtiene el ID de un equipo por su nombre
 * @param PDO $conn Conexión a la base de datos
 * @param string $nom Nombre del equipo
 * @return int ID del equipo
 */
function getTeamID($conn, $nom)
{
    $stmt = $conn->prepare("SELECT id FROM equips WHERE nom = :nom");
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();
    return $stmt->fetchColumn(); // Retorna ID equip
}

/**
 * Obtiene el ID de la liga a la que pertenece un equipo
 * @param PDO $conn Conexión a la base de datos
 * @param int $equipo_id ID del equipo
 * @return int ID de la liga
 */
function getLigaID($conn, $equipo_id)
{
    $sql = "SELECT lliga_id FROM equips WHERE id = :equipo_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':equipo_id', $equipo_id);
    $stmt->execute();

    return $stmt->fetchColumn(); // Retorna la ID de la liga
}

/* function getLeagueName($equipName, $conn) {
    $sql = "SELECT l.nom as lliga
            FROM equips e 
            JOIN lligues l ON e.lliga_id = l.id 
            WHERE e.nom = :equip";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':equip', $equipName, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchColumn();
} */

/**
 * Obtiene el nombre de la liga de un equipo
 * @param string $equipLocal Nombre del equipo local
 * @param PDO $conn Conexión a la base de datos
 * @return string Nombre de la liga
 */
function getLeagueNameByTeam($equipLocal, $conn)
{
    // Obtener el nom de la lliga del equip favorit
    $query = $conn->prepare("SELECT lligues.nom AS lliga FROM equips 
    JOIN lligues ON equips.lliga_id = lligues.id 
    WHERE equips.nom = :equipLocal");
    $query->bindParam(':equipLocal', $equipLocal);

    $query->execute();

    $nomLliga = $query->fetch(PDO::FETCH_COLUMN);
    return $nomLliga; // Return del nom de la lliga exclusivament
}

/**
 * Obtiene lista de partidos con paginación y filtros
 * @param PDO $conn Conexión a la base de datos
 * @param string $lliga Nombre de la liga
 * @param int $limit Límite de resultados
 * @param int $offset Desplazamiento para paginación
 * @param string|null $equipFavorit Equipo favorito para filtrar
 * @param string $orderColumn Columna para ordenar
 * @param string $orderDirection Dirección del ordenamiento
 * @return array Lista de partidos
 */
function getPartits($conn, $lliga, $limit, $offset, $equipFavorit = null, $orderColumn = 'p.data', $orderDirection = 'DESC')
{
    $baseSelect = "SELECT p.id, p.data, e_local.nom AS equip_local, e_visitant.nom AS equip_visitant, 
                   p.gols_local, p.gols_visitant, p.jugat, l.nom AS lliga,
                   a.id as article_id, a.title as article_title, 
                   a.content as article_content, a.user_id as article_user_id";
    $baseFrom = "FROM partits p
                 JOIN equips e_local ON p.equip_local_id = e_local.id
                 JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                 JOIN lligues l ON p.liga_id = l.id
                 LEFT JOIN articles a ON p.id = a.match_id";

    if ($equipFavorit) {
        $sql = "$baseSelect $baseFrom 
                WHERE (e_local.nom = :equip OR e_visitant.nom = :equip)
                AND l.nom = :lliga
                ORDER BY " . $orderColumn . " " . $orderDirection . "
                LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':equip', $equipFavorit, PDO::PARAM_STR);
    } else {
        $sql = "$baseSelect 
                $baseFrom
                WHERE l.nom = :lliga
                ORDER BY " . $orderColumn . " " . $orderDirection . "
                LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
    }
    $stmt->bindParam(':lliga', $lliga, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $partits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $partits;
}

/**
 * Cuenta el total de partidos según filtros
 * @param PDO $conn Conexión a la base de datos
 * @param string $lliga Nombre de la liga
 * @param string|null $equipFavorit Equipo favorito para filtrar
 * @return int Total de partidos
 */
function getTotalPartits($conn, $lliga, $equipFavorit = null)
{
    if ($equipFavorit) {
        $sql = "SELECT COUNT(*) 
                FROM partits p
                JOIN equips e_local ON p.equip_local_id = e_local.id
                JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                JOIN lligues l ON p.liga_id = l.id
                WHERE (e_local.nom = :equip OR e_visitant.nom = :equip)
                AND l.nom = :lliga";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':equip', $equipFavorit, PDO::PARAM_STR);
    } else {
        $sql = "SELECT COUNT(*) 
                FROM partits p
                JOIN lligues l ON p.liga_id = l.id
                WHERE l.nom = :lliga";
        $stmt = $conn->prepare($sql);
    }
    $stmt->bindValue(':lliga', $lliga, PDO::PARAM_STR);
    $stmt->execute();
    $totalPartits = $stmt->fetchColumn();
    $stmt->closeCursor();
    return $totalPartits;
}

/**
 * Inserta un nuevo artículo asociado a un partido
 * @param PDO $conn Conexión a la base de datos
 * @param int $match_id ID del partido
 * @param string $title Título del artículo
 * @param string $content Contenido del artículo
 * @param int $user_id ID del usuario autor
 * @return bool True si se insertó correctamente
 */
function insertArticle($conn, $match_id, $title, $content, $user_id)
{
    $sql = "INSERT INTO articles (match_id, user_id, title, content) 
            VALUES (:match_id, :user_id, :title, :content)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':match_id', $match_id);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    return $stmt->execute();
}

/**
 * Actualiza un artículo existente
 * @param PDO $conn Conexión a la base de datos
 * @param int $match_id ID del partido
 * @param string $title Nuevo título
 * @param string $content Nuevo contenido
 * @param int $user_id ID del usuario que actualiza
 * @return bool True si se actualizó correctamente
 */
function updateArticle($conn, $match_id, $title, $content, $user_id)
{
    $sql = "UPDATE articles 
            SET title = :title, 
                content = :content, 
                user_id = :user_id 
            WHERE match_id = :match_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':match_id', $match_id, PDO::PARAM_INT);
    return $stmt->execute();
}

/**
 * Obtiene un artículo por el ID del partido
 * @param PDO $conn Conexión a la base de datos
 * @param int $match_id ID del partido
 * @return array|false Datos del artículo o false si no existe
 */
function getArticleByMatchId($conn, $match_id)
{
    $sql = "SELECT * FROM articles WHERE match_id = :match_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':match_id', $match_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtiene el nombre de usuario por su ID
 * @param PDO $conn Conexión a la base de datos
 * @param int $user_id ID del usuario
 * @return string Nombre del usuario
 */
function getUsernameById($conn, $user_id)
{
    $sql = "SELECT nom_usuari FROM usuaris WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

/**
 * Busca partidos por término de búsqueda
 * @param PDO $conn Conexión a la base de datos
 * @param string $term Término de búsqueda
 * @return array Lista de partidos que coinciden
 */
function searchBarQuery($conn, $term)
{
    $sql = "SELECT p.id, e_local.nom AS equip_local, e_visitant.nom AS equip_visitant, p.data
                    FROM partits p
                    JOIN equips e_local ON p.equip_local_id = e_local.id
                    JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                    WHERE e_local.nom LIKE :term
                    OR e_visitant.nom LIKE :term
                    ORDER BY p.data DESC
                    LIMIT 5";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['term' => '%' . $term . '%']);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene todos los partidos de la base de datos
 * @param PDO $conn Conexión a la base de datos
 * @return array Array con todos los partidos
 */
function getAllMatches(PDO $conn): array
{
    try {
        $stmt = $conn->prepare("SELECT * FROM partits");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en getAllMatches: " . $e->getMessage());
        return [];
    }
}

/**
 * Crea un nuevo partido
 * @param PDO $conn Conexión a la base de datos
 * @param array $data Datos del partido (equipo_local, equipo_visitante, fecha)
 * @return bool True si se creó correctamente
 */
function crearPartit($conn, $data)
{
    try {
        $sql = "INSERT INTO partits (equip_local_id, equip_visitant_id, data, gols_local, gols_visitant, jugat, liga_id) 
                VALUES (:equip_local_id, :equip_visitant_id, :data, :gols_local, :gols_visitant, :jugat, :liga_id)";

        $stmt = $conn->prepare($sql);

        // Crear variables temporales para bindParam
        $equip_local_id = getTeamID($conn, $data['equipo_local']); // Convertir nombre a ID
        $equip_visitant_id = getTeamID($conn, $data['equipo_visitante']); // Convertir nombre a ID
        $fecha = $data['fecha'];
        $goles_local = $data['goles_local'] ?? 0;
        $goles_visitante = $data['goles_visitante'] ?? 0;
        $jugado = $data['jugado'] ?? 0;
        $liga_id = $data['liga_id'];

        // Vincular parámetros usando las variables temporales
        $stmt->bindParam(':equip_local_id', $equip_local_id);
        $stmt->bindParam(':equip_visitant_id', $equip_visitant_id);
        $stmt->bindParam(':data', $fecha);
        $stmt->bindParam(':gols_local', $goles_local);
        $stmt->bindParam(':gols_visitant', $goles_visitante);
        $stmt->bindParam(':jugat', $jugado);
        $stmt->bindParam(':liga_id', $liga_id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error en crearPartit: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Actualiza un partido existente
 * @param PDO $conn Conexión a la base de datos
 * @param int $id ID del partido a actualizar
 * @param array $data Nuevos datos del partido
 * @return bool True si se actualizó correctamente
 */
function actualitzarPartit(PDO $conn, int $id, array $data): bool
{
    try {
        $sql = "UPDATE partits 
                SET equip_local_id = :equip_local_id,
                    equip_visitant_id = :equip_visitant_id,
                    data = :data,
                    gols_local = :gols_local,
                    gols_visitant = :gols_visitant,
                    jugat = :jugat,
                    liga_id = :liga_id
                WHERE id = :id";

        // Crear variables temporales para bindParam
        $equip_local_id = getTeamID($conn, $data['equipo_local']);
        $equip_visitant_id = getTeamID($conn, $data['equipo_visitante']);
        $fecha = $data['fecha'];
        $goles_local = $data['goles_local'] ?? 0;
        $goles_visitante = $data['goles_visitante'] ?? 0;
        $jugado = $data['jugado'] ?? 0;
        $liga_id = $data['liga_id'];

        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':equip_local_id', $equip_local_id);
        $stmt->bindParam(':equip_visitant_id', $equip_visitant_id);
        $stmt->bindParam(':data', $fecha);
        $stmt->bindParam(':gols_local', $goles_local);
        $stmt->bindParam(':gols_visitant', $goles_visitante);
        $stmt->bindParam(':jugat', $jugado);
        $stmt->bindParam(':liga_id', $liga_id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error en actualitzarPartit: " . $e->getMessage());
        throw $e;
    }
}
