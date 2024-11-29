<?php
// Alexis Boisset

function insertPartido($conn, $equipo_local_id, $equipo_visitante_id, $liga_id, $fecha, $goles_local, $goles_visitante)
{
    global $conn;
    $jugado = (!is_null($goles_local) && !is_null($goles_visitante)) ? 1 : 0;
    $sql = "INSERT INTO partits (equip_local_id, equip_visitant_id, liga_id, data, gols_local, gols_visitant, jugat) 
            VALUES (:equipo_local_id, :equipo_visitante_id, :liga_id, :fecha, :goles_local, :goles_visitante, :jugado)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':equipo_local_id', $equipo_local_id);
    $stmt->bindParam(':equipo_visitante_id', $equipo_visitante_id);
    $stmt->bindParam(':liga_id', $liga_id); // Añade este parámetro
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':goles_local', $goles_local);
    $stmt->bindParam(':goles_visitante', $goles_visitante);
    $stmt->bindParam(':jugado', $jugado);

    return $stmt; // Retorna el statement para ejecutar después
}

function updatePartido($conn, $id, $equipo_local_id, $equipo_visitante_id, $fecha, $goles_local, $goles_visitante)
{
    global $conn;
    $jugado = (!is_null($goles_local) && !is_null($goles_visitante)) ? 1 : 0;
    $sql = "UPDATE partits 
            SET equip_local_id = :equipo_local_id, equip_visitant_id = :equipo_visitante_id, data = :fecha, 
                gols_local = :goles_local, gols_visitant = :goles_visitante, jugat = :jugado 
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':equipo_local_id', $equipo_local_id);
    $stmt->bindParam(':equipo_visitante_id', $equipo_visitante_id);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':goles_local', $goles_local);
    $stmt->bindParam(':goles_visitant', $goles_visitante);
    $stmt->bindParam(':jugado', $jugado);

    return $stmt; // Retorna el statement per executar-lo després
}

// Agafar dades dels partits
function consultarPartido($conn, $id)
{
    global $conn;
    $sql = "SELECT * FROM partits WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    $stmt->execute();
    return $stmt; // Retorna el statement per a futures manipulacions
}

// Delete per esborrar partits
function deletePartit($conn, $partit_id)
{
    global $conn;
    $sql = "DELETE FROM partits WHERE id = :partit_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':partit_id', $partit_id, PDO::PARAM_INT);
    return $stmt->execute();
}

// Funció per guardar la predicció en la base de dades (WORK IN PROGRESS)
function guardarPrediccio($conn, $partit_id, $usuari_id, $gols_local, $gols_visitant)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO prediccions (partit_id, usuari_id, gols_local, gols_visitant) VALUES (:partit_id, :usuari_id, :gols_local, :gols_visitant)");

    // Vincular params
    $stmt->bindParam(':partit_id', $partit_id);
    $stmt->bindParam(':usuari_id', $usuari_id);
    $stmt->bindParam(':gols_local', $gols_local);
    $stmt->bindParam(':gols_visitant', $gols_visitant);

    // Executar i tornar resultat
    return $stmt->execute();
}

// Funció per obtenir el nom d'un equip
function getTeamName($conn, $id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT nom FROM equips WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Funció per obtenir l'ID d'un equip per fer-ho DATA BASE READABLE (no sé si existeix el terme)
function getTeamID($conn, $nom)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM equips WHERE nom = :nom");
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();
    return $stmt->fetchColumn(); // Retorna ID equip
}

// Funció per obtenir l'ID d'una lliga per fer-la DATA BASE READABLE (sona bé)
function getLigaID($conn, $equipo_id)
{
    global $conn;
    $sql = "SELECT lliga_id FROM equips WHERE id = :equipo_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':equipo_id', $equipo_id);
    $stmt->execute();

    return $stmt->fetchColumn(); // Retorna la ID de la liga
}


function getLeagueName($equipLocal, $conn)
{
    global $conn;
    // Obtener el nom de la lliga del equip favorit
    $query = $conn->prepare("SELECT lligues.nom AS lliga FROM equips 
    JOIN lligues ON equips.lliga_id = lligues.id 
    WHERE equips.nom = :equipLocal");
    $query->bindParam(':equipLocal', $equipLocal);
    
    $query->execute();
    
    $nomLliga = $query->fetch(PDO::FETCH_COLUMN);
    return $nomLliga; // Return del nom de la lliga exclusivament
}

function getPartits($conn, $lliga, $limit, $offset, $equipFavorit = null, $orderColumn = 'p.data', $orderDirection = 'DESC') {
   
    if ($equipFavorit) {
        $sql = "SELECT p.id, p.data, e_local.nom AS equip_local, e_visitant.nom AS equip_visitant, 
                       p.gols_local, p.gols_visitant, p.jugat, l.nom AS lliga
                FROM partits p
                JOIN equips e_local ON p.equip_local_id = e_local.id
                JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                JOIN lligues l ON p.liga_id = l.id
                WHERE (e_local.nom = :equip OR e_visitant.nom = :equip)
                AND l.nom = :lliga
                ORDER BY " . $orderColumn . " " . $orderDirection . "
                LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':equip', $equipFavorit, PDO::PARAM_STR);
    } else {
        $sql = "SELECT p.id, p.data, e_local.nom AS equip_local, e_visitant.nom AS equip_visitant, 
                       p.gols_local, p.gols_visitant, p.jugat, l.nom AS lliga
                FROM partits p
                JOIN equips e_local ON p.equip_local_id = e_local.id
                JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                JOIN lligues l ON p.liga_id = l.id
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

function getTotalPartits($lliga, $equipFavorit = null) {
    global $conn;
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