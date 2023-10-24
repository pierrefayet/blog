<?php

namespace App\Model;

use PDO;
use PDOException;

class Modifypost
{
    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function modifyPost($title, $content, $postId): bool
    {
        try {
            $stmt = $this->database->prepare('UPDATE posts SET title = :title, content = :content, creation_date = NOW() WHERE id = :postId');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':postId', $postId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la modification d\'un post : ' . $e->getMessage());
            return false;
        }
    }
}