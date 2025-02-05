<?php
// Alexis Boisset
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

    public function getSharedArticleByToken($token)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT a.*, m.*, sa.show_title, sa.show_content 
                FROM shared_articles sa
                JOIN articles a ON sa.article_id = a.id
                JOIN partits m ON sa.match_id = m.id
                WHERE sa.token = ?
            ");

            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getSharedArticleByToken: " . $e->getMessage());
            throw $e;
        }
    }

    public function checkDuplicateArticle($matchId, $title, $userId)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM articles WHERE match_id = ? AND title = ? AND user_id = ?");
        $stmt->execute([$matchId, $title, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUserArticle($matchId, $userId, $title, $content)
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (match_id, user_id, title, content, created_at) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$matchId, $userId, $title, $content]);
    }

    public function createUserArticleWithTransaction($matchId, $userId, $title, $content, $token)
    {
        try {
            $this->pdo->beginTransaction();

            // Crear el nuevo artículo
            $articleCreated = $this->createUserArticle($matchId, $userId, $title, $content);

            if (!$articleCreated) {
                throw new Exception("Error al crear el artículo");
            }

            // Eliminar el artículo compartido
            $articleDeleted = $this->deleteSharedArticle($token);

            if (!$articleDeleted) {
                throw new Exception("Error al eliminar el artículo compartido");
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Error en la transacción: " . $e->getMessage());
        }
    }

    public function deleteSharedArticle($token)
    {
        $stmt = $this->pdo->prepare("DELETE FROM shared_articles WHERE token = ?");
        return $stmt->execute([$token]);
    }
}
