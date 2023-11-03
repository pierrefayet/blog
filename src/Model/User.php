<?php

namespace App\Model;

use PDO;
use PDOException;

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;

    }

    public function hasPermission(): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE user_status_id = :role");
            $stmt->bindParam(':$role', $role);
            $stmt->execute();
            return $resultUsers = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération du statut : ' . $e->getMessage());
            return [];
        }
    }
}