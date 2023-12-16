<?php

namespace App\Service;

class SecurityCsrf
{
    public static function check(array $data): bool
    {
        if (isset($_SESSION['csrf']) && $_SESSION['csrf'] !== $_POST['csrf']) {
            http_response_code(403);
            unset($_SESSION['csrf']);
            return false;

        }
        return true;
        
    }
}
