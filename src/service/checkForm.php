<?php

namespace App\service;

class checkForm
{
    static function checkFormMail($from_name, $from_email, $subject, $message): array
    {
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_string($from_name) || !is_string($from_email) || !is_string($subject) || !is_string($message)) {
            $params['errorMessage'] = 'Tous les champs doivent être des chaînes de caractères.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($from_name) || empty($from_email) || empty($subject) || empty($message)) {
            $params['errorMessage'] = 'Tous les champs sont obligatoires.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
            $params['errorMessage'] = 'L\'adresse email n\'est pas valide.';
        }

        return $params;
    }

    static function checkFormLogin($username, $password): array
    {
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_string($username) || !is_string($password)) {
            $params['errorMessage'] = 'Tous les champs doivent être des chaînes de caractères.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_string($username) || !is_string($password)) {
            $params['errorMessage'] = 'Tous les champs sont obligatoires.';
        }

        return $params;
    }

    static function checkFormRegister($username, $email , $password): array
    {
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_string($username) || !is_string($email) || !is_string($password)) {
            $params['errorMessage'] = 'Tous les champs doivent être des chaînes de caractères.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_string($username) || !is_string($email) || !is_string($password)) {
            $params['errorMessage'] = 'Tous les champs sont obligatoires.';
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/', $password)) {
            $params['errorMessage'] = 'Le mot de passe doit contenir au moins une majuscule, un caractère spécial et avoir une longueur minimale de 8 caractères.';
        }

        return $params;
    }
}