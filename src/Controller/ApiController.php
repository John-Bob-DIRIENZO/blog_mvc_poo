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

        $user = $userManager->checkCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        $postId = !empty($this->params['id']) ? $this->params['id'] : false;
        $post = $postManager->postExists($postId) ? $postManager->getPostById($postId) : false;

        parse_str(file_get_contents('php://input'), $_PUT);

        // GET
        if ($this->HTTPRequest->method() === 'GET') :
            switch ($postId) {
                case false:
                    return $this->renderJSON($postManager->getPosts(null, true));

                case true:
                    $post = $postManager->getPostById($postId, true);
                    if (empty($post)) {
                        return new ErrorController('noRoute');
                    }
                    return $this->renderJSON($post);
            }
        endif;

        // POST
        if ($this->HTTPRequest->method() === 'POST' && !$postId) :

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
        if ($this->HTTPRequest->method() === 'PUT' && $postId && $post) :

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
        if ($this->HTTPRequest->method() === 'PATCH' && $postId && $post) :

            if ($user && (!empty($_PUT['title']) || !empty($_PUT['content'])) && $user->havePostRights($post)) {
                $postTitle = empty($_PUT['title']) ? $post->getTitle() : $_PUT['title'];
                $postContent = empty($_PUT['content']) ? $post->getContent() : $_PUT['content'];

                $post->setTitle($postTitle);
                $post->setContent($postContent);
                $success = $postManager->updatePost($post, true);

                if ($success) {
                    return $this->renderJSON($success);
                }
            }
        endif;

        // DELETE
        if ($this->HTTPRequest->method() === 'DELETE' && $postId && $post && $user && $user->havePostRights($post)) :
            $success = $postManager->deletePost($postId);

            if ($success) {
                return $this->renderJSON([
                    "status" => 1,
                    "message" => 'Post deleted'
                ]);
            }
        endif;

        // If something goes wrong :
        $this->HTTPResponse->unauthorized('Basic Auth, Needed arguments : "title" & "content"');
    }
}