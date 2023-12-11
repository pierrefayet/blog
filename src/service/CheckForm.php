<?php

namespace App\service;

class CheckForm
{
    static function checkFormMail( string $from_name, string $from_email, string $subject, string $message): array
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

    static function checkFormLogin(string $username, string $password): array
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

    static function checkFormRegister(string $username, string $email , string $password): array
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

    static function checkFormCommentForm(string $content): array
    {
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_string($content)) {
            $params['errorMessage'] = 'Tous les champs doivent être des chaînes de caractères.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($content)) {
            $params['errorMessage'] = 'Le champs contenu du commentaire  est obligatoires.';
        }

        return $params;
    }

    static function checkFormPostForm(string $title, string $intro, string $content, string $author): array
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($title)) {
                $errors[] = 'Le titre est obligatoire.';
            }
            if (empty($intro)) {
                $errors[] = 'Le chapô est obligatoire.';
            }
            if (empty($content)) {
                $errors[] = 'Le contenu est obligatoire.';
            }
            if (empty($author)) {
                $errors[] = 'L\'auteur est obligatoire.';
            }
        }

        return $errors;
    }

}