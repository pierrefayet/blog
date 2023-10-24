<?php

namespace App\Model;

use PDO;

class GetAllPosts
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllPosts()
    {
        $db = "SELECT * FROM posts";
        $stmt = $this->db->query($db);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}