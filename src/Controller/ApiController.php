<?php


namespace Controller;


use Entity\Post;
use Model\PostManager;
use Model\UserManager;

class ApiController extends BaseController
{
    public function executePosts()
    {
        $postManager = new PostManager();
        $userManager = new UserManager();
        // GET
        if ($this->HTTPRequest->method() === 'GET') :
            switch (empty($this->params['id'])) {
                case true:
                    return $this->renderJSON($postManager->getPosts(null, true));

                case false:
                    $post = $postManager->getPostById($this->params['id'], true);
                    if (empty($post)) {
                        return new ErrorController('noRoute');
                    }
                    return $this->renderJSON($post);
            }
        endif;

        // POST
        if ($this->HTTPRequest->method() === 'POST' && empty($this->params['id'])) :
            $user = $userManager->checkCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

            if ($user && !empty($_POST['title']) && !empty($_POST['content'])) {
                $newPost = new Post(array(
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    'authorId' => $user->getId()
                ));
                $success = $postManager->addPost($newPost, true);

                if ($success) {
                    return $this->renderJSON($success);
                }
            }
        endif;

        // PUT (renvoyer toute l'entité)
        if ($this->HTTPRequest->method() === 'PUT' && !empty($this->params['id']) && $postManager->postExists($this->params['id'])) :

            $user = $userManager->checkCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
            $post = $postManager->getPostById($this->params['id']);

            parse_str(file_get_contents('php://input'), $_PUT);

            if ($user && !empty($_PUT['title']) && !empty($_PUT['content']) && $user->havePostRights($post)) {
                $post->setTitle($_PUT['title']);
                $post->setContent($_PUT['content']);
                $success = $postManager->updatePost($post, true);

                if ($success) {
                    return $this->renderJSON($success);
                }
            }
        endif;

        // PATCH (renvoyer que l'élément à modifier)


        // Si quelque chose déconne :
        $this->HTTPResponse->unauthorized('Basic Auth, Needed arguments : "title" & "content"');
    }
}