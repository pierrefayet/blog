<?php

namespace App\Model;

use PDO;
use PDOException;

class InsertPost
{
    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function insertPost($title, $content): bool
    {
        try {
            $stmt = $this->database->prepare('INSERT INTO posts (title, content, creation_date) VALUES (:title, :content, NOW())');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de l\'insertion d\'un post : ' . $e->getMessage());
            return false;
        }
    }
}