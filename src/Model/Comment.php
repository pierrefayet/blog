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
        return $stmt->execute();
    }

    public function modifyStatusComment($commentId): bool
    {
            $stmt = $this->db->prepare('UPDATE comments SET is_approved = 1 WHERE id = :commentId');
            $stmt->bindParam(':commentId', $commentId);
            return $stmt->execute();
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
            $getComment = $stmt->fetch(PDO::FETCH_ASSOC);
            return $getComment;
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération du commentaire : ' . $e->getMessage());
            return [];
        }
    }

    public function getPost($commentId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT comment_post_id FROM comments WHERE id = :commentId");
            $stmt->bindParam(':commentId', $commentId);
            $stmt->execute();
            $getPost= $stmt->fetch(PDO::FETCH_ASSOC);
            return $getPost[0];
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération du commentaire : ' . $e->getMessage());
            return [];
        }
    }

    public function getAllComments(): array
    {
            $stmt = $this->db->prepare(
                "SELECT comments.id, comments.content, comments.creation_date, users.username , posts.title
                       FROM comments
                       INNER JOIN users ON comments.comment_user_id = users.user_id
                       INNER JOIN posts ON comments.comment_post_id = posts.id
                       WHERE comments.is_approved = 0
                       ");
            $stmt->execute();
            $allComment = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $allComment;
    }
}