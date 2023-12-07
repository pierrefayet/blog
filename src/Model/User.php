<?php

namespace App\Model;

use PDO;

class User
{
    public PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertUser(string $username, string $email, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role) 
                       VALUES (:username,:email, :password, "user")
            ');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        return $stmt->execute();
    }

    public function modifyUser(string $email, string $password): bool
    {
        $stmt = $this->db->prepare('UPDATE user SET email = :email, password = :password WHERE id = :userId');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function checkUser(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT role, user_id, username, password 
                   FROM users 
                   WHERE username = :username
        ');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['password'])) {
            return $row;
        }

        return null;
    }

    public function login(string $username, string $password): bool
    {
        $user = $this->checkUser($username, $password);
        if ($user) {
            $_SESSION['username'] = $username;
            $_SESSION['userId'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged'] = true;
            header('Location: http://localhost:8080/src/index.php?method=home&controller=HomePageController');
            return true;
        }

        return false;
    }
}
