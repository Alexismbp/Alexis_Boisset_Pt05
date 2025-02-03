<?php
function getSharedArticleData($token)
{
    $conn = Database::getInstance();

    $sql = "SELECT sa.*, 
            a.title, a.content, 
            p.data, 
            e_local.nom AS equip_local, 
            e_visitant.nom AS equip_visitant
            FROM shared_articles sa
            INNER JOIN articles a ON sa.article_id = a.id
            INNER JOIN partits p ON sa.match_id = p.id
            INNER JOIN equips e_local ON p.equip_local_id = e_local.id
            INNER JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
            WHERE sa.token = :token";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['token' => $token]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllSharedArticles($conn)
{
    $stmt = $conn->prepare("
    SELECT sa.id, sa.token, a.title AS article_title, 
           p.id AS match_id, e_local.nom AS equipo_local, 
           e_visitant.nom AS equipo_visitante, p.data, 
           sa.show_title, sa.show_content, sa.created_at
    FROM shared_articles sa
    JOIN articles a ON sa.article_id = a.id
    JOIN partits p ON sa.match_id = p.id
    JOIN equips e_local ON p.equip_local_id = e_local.id
    JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
