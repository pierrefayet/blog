<?php

namespace App\Model;

use PDO;
use PDOException;

class Connection
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertUser($email, $password): bool
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare('INSERT INTO user (email, password) VALUES (:email, :password)');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de l\'insertion d\'un utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    public function modifyUser($email, $password): bool
    {
        try {
            $stmt = $this->db->prepare('UPDATE user SET email = :email, password = :password WHERE id = :userId');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
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

    public function verifyUser($email, $password): bool
    {
        $stmt = $this->db->prepare('SELECT password FROM user WHERE user_status_id = :status');
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {

            return true;
        }

        return false;
    }
}