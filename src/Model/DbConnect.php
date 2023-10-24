<?php

namespace App\Model;

use Exception;
use PDO;
use PDOException;

/**
 * Class ConnectDB
 * @package App\Model
 */
class DbConnect
{
    private PDO $db;

    /**
     * @throws Exception
     */
    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getDb(): PDO
    {
        return $this->db;
    }

    public function query($sql): false|\PDOStatement
    {
        return $this->db->query($sql);
    }
}

