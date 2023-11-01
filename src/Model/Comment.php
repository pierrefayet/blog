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

    public function insertComment($author, $content): bool
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO comment (author, content, creation_date) VALUES (:author, :content, NOW())');
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':content', $content);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de l\'insertion du commentaire : ' . $e->getMessage());
            return false;
        }
    }

    public function modifyComment($author, $content): bool
    {
        try {
            $stmt = $this->db->prepare('UPDATE user SET author = :author, content = :content WHERE id = :commentId');
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
            $stmt = $this->db->prepare('DELETE FROM user WHERE id = :commentId');
            $stmt->bindParam(':userId', $commentId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function verifyUser($email, $password): bool
    {
        $stmt = $this->db->prepare('SELECT password FROM user WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {

            return true;
        }

        return false;
    }
}