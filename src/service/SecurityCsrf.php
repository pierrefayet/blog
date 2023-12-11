<?php

namespace App\service;

class SecurityCsrf
{
    public static function check(array $data): bool
    {
        if (isset($_SESSION['csrf']) && $_SESSION['csrf'] !== $_POST['csrf']) {
            http_response_code(403);

            return false;
        }
        return true;
    }
}