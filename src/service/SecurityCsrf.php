<?php

namespace App\service;

class SecurityCsrf
{
    public static function check(array $data): bool
    {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
            http_response_code(403);
            return false;
        }
        return true;
    }
}