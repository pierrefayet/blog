<?php

namespace App\service;

class Security
{
    public static function checkCsrf()
    {
        http_response_code(403);
        return false;
    }
}