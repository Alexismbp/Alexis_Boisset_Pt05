<?php
// Alexis Boisset
// user_model.php

/* Aquest model consta de funcions amb diferents returns
   Alguns returns tornen la query llesta per executar 
   mentre que altres retornan l'informació ja executada i associada a un array */


function registerUser($username, $email, $password, $equipFavorit, $conn)
{

    // Validar si l'usuari ja existeix
    $query = $conn->prepare("SELECT * FROM usuaris WHERE correu_electronic = :email");
    $query->bindParam(':email', $email);

    $query->execute();

    if ($query->rowCount() > 0) {
        return false; // L'usuari ja existeix
    }

    // Insertar el nou usuari
    $insertQuery = $conn->prepare("INSERT INTO usuaris (id, nom_usuari, correu_electronic, contrasenya, equip_favorit) 
                                    VALUES (:id, :username, :email, :password, :team)");
    $insertQuery->bindParam(':id', ultimaIdDisponible($conn));
    $insertQuery->bindParam(':username', $username);
    $insertQuery->bindParam(':email', $email);
    $insertQuery->bindParam(':password', $password);
    $insertQuery->bindParam(':team', $equipFavorit);

    return $insertQuery->execute();
}

/* 
   No es un error que aquesta funció estigui aquí 
   només s'utilitza en fitxers de login i register
   funció necesaria per el correcte funcionament del programa
*/

function getLeagueName($equipFavorit, $conn)
{
    // Obtener el nom de la lliga del equip favorit
    $query = $conn->prepare("SELECT lligues.nom AS lliga FROM equips 
    JOIN lligues ON equips.lliga_id = lligues.id 
    WHERE equips.nom = :equipFavorit");
    $query->bindParam(':equipFavorit', $equipFavorit);

    $query->execute();

    $nomLliga = $query->fetch(PDO::FETCH_COLUMN);
    return $nomLliga; // Return del nom de la lliga exclusivament
}

function getUserData($email, $conn)
{
    // Preparo la consulta només per comprobar el email
    $conn;
    $sql = $conn->prepare("SELECT correu_electronic 
                           FROM usuaris 
                           WHERE correu_electronic = :email");
    $sql->bindParam(':email', $email);
    $sql->execute();
    $dbEmail = $sql->fetch(PDO::FETCH_ASSOC);

    // Si el correu electronic existeix, agafa dades de L'usuari que son necessaries pel correcte funcionament de la Web
    if ($dbEmail['correu_electronic'] === $email) {

        $sql = $conn->prepare("SELECT id, nom_usuari, equip_favorit, contrasenya 
                               FROM usuaris 
                               WHERE correu_electronic = :email");
        $sql->bindParam(':email', $email); // Busquem per clau primaria únicament

        $sql->execute();
        $userData = $sql->fetch(PDO::FETCH_ASSOC); // Array associatiu per poder extreure les dades fàcilment per nom de columna
        return $userData; // Retornem la informació
    } else {
        return false; // El correu electrònic no consta a la base de dades
    }
}

// Funció que serveix per a omplenar les diferències que hi ha entre les ID d'una fila a una altra
function ultimaIdDisponible($conn)
{
    $contador = 1;

    $query = "SELECT id 
              FROM usuaris 
              ORDER BY id asc";
    $allId = $conn->prepare($query);

    $allId->execute();

    // Fetching IDs into an array
    $idDisponible = $allId->fetchAll(PDO::FETCH_COLUMN, 0);

    foreach ($idDisponible as $idActual) {
        if ($contador != $idActual) {
            return $contador; // Retorna l'ID disponible si no coincideix
        }
        $contador++;
    }

    return $contador; // Retorna el contador si no hi ha buits
}
