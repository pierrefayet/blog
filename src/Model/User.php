<?php

namespace App\Model;

use PDO;
use PDOException;

class User
{
    public PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertUser($username, $email, $password): bool
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare('INSERT INTO users (username, email, password, user_status_id) VALUES (:username,:email, :password, "1")');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            return $stmt->execute();
        } catch (PDOException $e) {
            $logFilePath = __DIR__ . '/logs/error.log';
            error_log('Erreur lors de l\'insertion d\'un utilisateur : ' . $e->getMessage(), 3, $logFilePath);
            return false;
        }
    }

    public function modifyUser($email, $password): bool
    {
        try {
            $stmt = $this->db->prepare('UPDATE user SET email = :email, password = :password WHERE id = :userId');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':userId', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la modification d\'un utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId): bool
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM user WHERE id = :userId');
            $stmt->bindParam(':userId', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function checkUser($username, $password, $userId): bool
    {
        $stmt = $this->db->prepare('SELECT user_id, username FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($row);
        if (password_verify($password, $row['password'])) {
            return true;
        }

        return false;
    }

    public function checkStatusUser($email, $password): bool
    {
        $stmt = $this->db->prepare('SELECT user_status_id FROM user WHERE user_status_id = 1');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function getStatus($userId): array
    {
            $stmt = $this->db->prepare("SELECT user_status_id FROM users WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $resultUsers = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultUsers;
    }

    public function getUsername(): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username= :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération du nom de l\'utilisateur : ' . $e->getMessage());
            return [];
        }
    }
}