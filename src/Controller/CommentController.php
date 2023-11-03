<?php

namespace App\Controller;

use App\Model\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class CommentController
{
    private User $resultUsers;

    public function __construct(User $resultUsers)
    {
        $this->resultUsers = $resultUsers;
    }

    public function checkStatusForConnect()
    {
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);
        $template = $twig->load('security/connect.twig');
        foreach ($resultUsers as $resultUser) {
            if ($resultUser) {
                $resultUser['status'] = 'Admin';
            }
        }

    }
}