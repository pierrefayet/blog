<?php

namespace App\Model;

use PDO;

class Post
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertPost(string $title, string $intro, string $content, string $author): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO posts (title, intro, content, creation_date, author) 
                   VALUES (:title, :intro, :content, NOW(), :author)');

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':intro', $intro);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);
        return $stmt->execute();
    }

    public function modifyPost(string $title, string $intro, string $content, int $postId, string $author): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE posts 
                   SET title = :title, intro = :intro, content = :content, creation_date = NOW(), author = :author
                   WHERE id = :postId
            ');

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':intro', $intro);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':postId', $postId);
        return $stmt->execute();
    }

    public function getAllPosts(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM posts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSinglePost(int $postId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = :postId ORDER BY creation_date DESC");
        $stmt->bindParam(':postId', $postId);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        return $post;
    }

    public function getNewPosts(): array
    {
        $db = "SELECT * FROM posts ORDER BY creation_date DESC LIMIT 2";
        $stmt = $this->db->query($db);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePost(int $postId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM posts WHERE id = :postId');
        $stmt->bindParam(':postId', $postId);
        return $stmt->execute();
    }

    public function getAllComments(int $postId): array
    {
        $stmt = $this->db->prepare(
            "SELECT comments.content, comments.creation_date, users.username
                   FROM comments
                   INNER JOIN users ON comments.comment_user_id = users.user_id
                   WHERE comments.comment_post_id = :postId AND is_approved = 1
            ");

        $stmt->bindParam(':postId', $postId);
        $stmt->execute();
        $allComment = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $allComment;
    }

    public function deleteAllComment(int $commentId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM comments WHERE id = :commentId');
        $stmt->bindParam(':commentId', $commentId);
        return $stmt->execute();
    }
}
