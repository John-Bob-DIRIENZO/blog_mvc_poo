<?php


namespace Controller;


use Model\PostManager;

class FrontController extends BaseController
{
    public function executeIndex(int $number = 5)
    {
        $manager = new PostManager();
        $index = $manager->getPosts($number);

        return $this->render('Page d\'accueil', $index, 'Frontend/index');
    }

    public function executeShow()
    {
        $manager = new PostManager();
        $article = $manager->getPostById($this->params['id']);

        if (!$article) {
            $this->HTTPResponse->redirect('/');
        }

        return $this->render($article->getTitle(), ['article' => $article], 'Frontend/show');
    }
}