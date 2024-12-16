<?php
function getSharedArticleData($token)
{
    $conn = Database::getInstance();

    $sql = "SELECT sa.*, 
            a.title, a.content, 
            p.data, 
            e_local.nom AS equip_local, 
            e_visitant.nom AS equip_visitant,
            sa.show_title, sa.show_content
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
