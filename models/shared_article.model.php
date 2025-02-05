<?php

class SharedArticle
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function createSharedArticle($token, $article_id, $match_id, $show_title, $show_content)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("INSERT INTO shared_articles (token, article_id, match_id, show_title, show_content) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$token, $article_id, $match_id, $show_title, $show_content]);
            $this->pdo->commit();
            return $result;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function validateToken($token)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM shared_articles WHERE token = ?");
        $stmt->execute([$token]);
        return $stmt->fetchColumn() > 0;
    }
}
