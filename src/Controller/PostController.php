<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class PostController
{
    private Post $postModel; // On injecte le modèle
    private ?string $errorMessage = null;
    private ?string $successMessage = null;


    public function __construct(Post $postModel)
    {
        $this->postModel = $postModel; // on injecte le modèle dans le constructeur;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function add(): string
    {
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('post.twig');

        // Je soumet le formulaire pour ajouter un post ici
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['title']) && isset($_POST['content'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
            }
            // J'utilise le modèle pour ajouter le post
                $result = $this->postModel->insertPost($title, $content);
                $params = [];
            if ($result) {
                var_dump('un result trouvé');
               $params ['successMessage'] = 'L\'article a été ajouté avec succès.';
            } else {
                var_dump('pas de result trouvé');
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'article.';
            }
            var_dump('on passe ici');
            // J'affiche le formulaire d'ajout de post

            return $template->render($params);
        }
        return $template->render();
    }

    public function index(): string
    {
        $posts = $this->postModel->getAllPosts();

        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('listing.twig');
        return $template->render([
            'posts' => $posts,
        ]);
    }

    public function home(): string
    {

        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('homePage.twig');
        return $template->render();
    }

    public function about(): string
    {

        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('about.twig');
        return $template->render();
    }
}