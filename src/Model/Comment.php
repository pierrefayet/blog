<?php

namespace App\Model;

use PDO;
use PDOException;

class Comment
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertComment($postId, $userId, $content): bool
    {
        $stmt = $this->db->prepare('INSERT INTO comments (comment_post_id, comment_user_id, content, creation_date) VALUES (:postId, :userId, :content, NOW())');
        $stmt->bindParam(':postId', $postId);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':content', $content);
        $stmt->debugDumpParams();
        return $stmt->execute();
    }

    public function modifyComment($author, $content): bool
    {
        try {
            $stmt = $this->db->prepare('UPDATE comment SET author = :author, content = :content WHERE id = :commentId');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            return $stmt->execute();
        } catch (PDOException $e) {
             error_log('Erreur lors de la modification d\'un utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteComment($commentId): bool
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM comments WHERE id = :commentId');
            $stmt->bindParam(':commentId', $commentId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function getSingleComment($commentId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM comments WHERE id = :commentId");
            $stmt->bindParam(':$commentId', $commentId);
            $stmt->execute();
            $getComment= $stmt->fetch(PDO::FETCH_ASSOC);
            var_dump($getComment);
            return $getComment;
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération du commentaire : ' . $e->getMessage());
            return [];
        }
    }
}