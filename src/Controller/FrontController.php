<?php


namespace Controller;


use Model\PostManager;

class FrontController extends BaseController
{
    public function executeIndex(int $number = 5)
    {
        $manager = new PostManager();
        return $manager->getPosts($number);
    }

    public function executeShow()
    {
        $manager = new PostManager();
        $article = $manager->getPostById($this->id);

        if (!$article) {
            header('Location: /');
            exit();
        }

        return $this->render('coucou', ['article' => $article], 'Frontend/index');
    }
}