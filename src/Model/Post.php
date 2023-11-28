<?php

namespace App\Model;

use PDO;
use PDOException;

class Post
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertPost($title, $content): bool
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO posts (title, content, creation_date) VALUES (:title, :content, NOW())');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de l\'insertion d\'un post : ' . $e->getMessage());
            return false;
        }
    }

    public function modifyPost($title, $content, $postId): bool
    {
        try {
            $stmt = $this->db->prepare('UPDATE posts SET title = :title, content = :content, creation_date = NOW() WHERE id = :postId');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':postId', $postId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la modification d\'un post : ' . $e->getMessage());
            return false;
        }
    }

    public function getAllPosts(): array
    {
        try {
            $db = "SELECT * FROM posts";
            $stmt = $this->db->query($db);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des posts : ' . $e->getMessage());
            return [];
        }
    }

    public function getSinglePost($postId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = :postId");
            $stmt->bindParam(':postId', $postId);
            $stmt->execute();
            $post= $stmt->fetch(PDO::FETCH_ASSOC);
            return $post;
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération du post : ' . $e->getMessage());
            return [];
        }
    }

    public function getNewPosts(): array
    {
        try {
            $db = "SELECT * FROM posts ORDER BY creation_date DESC LIMIT 2";
            $stmt = $this->db->query($db);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des posts : ' . $e->getMessage());
            return [];
        }
    }

    public function deletePost($postId): bool
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM posts WHERE id = :postId');
            $stmt->bindParam(':postId', $postId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression du post : ' . $e->getMessage());
            return false;
        }
    }

    public function getAllComments($postId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT comments.content, comments.creation_date, users.username
                       FROM comments
                       INNER JOIN users ON comments.comment_user_id = users.user_id
                       WHERE comments.comment_post_id = :postId");
            $stmt->bindParam(':postId', $postId);
            $stmt->execute();
            $allComment = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $allComment;
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des commentaires : ' . $e->getMessage());
            return [];
        }
    }

    public function deleteAllComment($postId): bool
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM comments WHERE id = :commentId');
            $stmt->bindParam(':commentId', $commentId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression du post : ' . $e->getMessage());
            return false;
        }
    }
}
