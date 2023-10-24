<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Model\GetAllPosts;
use PDO;

class DisplayArticleController
{
    private GetAllPosts $GetAllPost;

    public function __construct(PDO $db)
    {
        $this->GetAllPost = new GetAllPosts($db);
    }

    public function displayArticle()
    {
        $posts = $this->GetAllPost->getAllPosts();

        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('listing.twig');
        return $template->render([
            'posts' => $posts,
        ]);
    }
}