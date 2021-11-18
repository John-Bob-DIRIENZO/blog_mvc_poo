<?php


namespace Controller;


use Entity\Post;
use Model\ImageManager;
use Model\PostManager;
use Model\UserManager;

class PostController extends BaseController
{
    /**
     * Shows the homepage with the lasts posts
     * @param int $number
     * @return mixed
     */
    public function executeShowMany(int $number = 5)
    {
        $manager = new PostManager();
        $index = $manager->getPosts($number);

        return $this->render('Page d\'accueil', $index, 'Frontend/index');
    }

    /**
     * Shows one post if it exists, homepage otherwise
     * @return mixed
     */
    public function executeShowOne()
    {
        $manager = new PostManager();
        $article = $manager->getPostById($this->params['id']);

        if (!$article) {
            $this->HTTPResponse->redirect('/');
        }

        return $this->render($article->getTitle(), ['article' => $article], 'Frontend/show');
    }

    /**
     * Checks authentication
     * Shows the post from if no $_POST
     * Writes post in DB otherwise
     * @return mixed
     */
    public function executeWritePost()
    {
        if (SecurityController::isAuthenticated() && !isset($_POST['title']) && !isset($_POST['content'])) {
            return $this->render('Write new article', [], 'Admin/write-article');
        } elseif (SecurityController::isAuthenticated() && isset($_POST['title']) && isset($_POST['content'])) {
            $newPost = new Post(array(
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'authorId' => SecurityController::getLoggedUser()->getId()
            ));

            $imageManager = new ImageManager();
            $image = $imageManager->uploadImage($_FILES['image']);
            if ($image) {
                $newPost->setImageId($image->getId());
            }

            $manager = new PostManager();
            $newPost = $manager->addPost($newPost);

            $this->HTTPResponse->redirect('/article/' . $newPost->getId());
        }

        $this->HTTPResponse->redirect('/login');
    }

    /**
     * Checks authentication
     * Deletes post from DB with associated comments
     */
    public function executeDeletePost(): void
    {
        $postManager = new PostManager();
        $post = $postManager->getPostById($this->params['id']);

        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($post)) {
            $postManager->deletePost($this->params['id']);
        }

        $this->HTTPResponse->redirect('/');
    }

    /**
     * Checks authentication
     * Shows update form if no $_POST
     * Updates post otherwise
     * @return mixed
     */
    public function executeUpdatePost()
    {
        $manager = new PostManager();
        $post = $manager->getPostById($this->params['id']);
        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($post) && !isset($_POST['title'])) {
            return $this->render('Update article', [
                'article' => $post
            ], 'Admin/update-article');
        } elseif (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($post) && isset($_POST['title']) && isset($_POST['content'])) {
            $newPost = new Post(array(
                'id' => $this->params['id'],
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ));

            $imageManager = new ImageManager();
            $image = $imageManager->uploadImage($_FILES['image']);

            if ($image) {
                $newPost->setImageId($image->getId());
            } else {
                $newPost->setImageId($post->getImageId());
            }

            $manager->updatePost($newPost);
        }

        $this->HTTPResponse->redirect('/article/' . $this->params['id']);
    }

    /**
     * The REST API for the /posts/:id route
     * @return ErrorController|void
     */
    public function executePostsApi()
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
                    $this->HTTPResponse->setCacheHeader(300);
                    isset($this->params['number']) ? $number = abs(intval($this->params['number'])) : $number = null;
                    $this->HTTPResponse->setCacheHeader(500);
                    $this->HTTPResponse->addHeader('Access-Control-Allow-Origin: *');
                    return $this->renderJSON($postManager->getPosts($number, true));

                case true:
                    $post = $postManager->getPostById($postId, true);
                    if (empty($post)) {
                        return new ErrorController('noRouteJSON');
                    }
                    $this->HTTPResponse->setCacheHeader(500);
                    $this->HTTPResponse->addHeader('Access-Control-Allow-Origin: *');
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
                    $this->HTTPResponse->setCacheHeader(500);
                    $this->HTTPResponse->addHeader('Access-Control-Allow-Origin: *');
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
                    $this->HTTPResponse->setCacheHeader(500);
                    $this->HTTPResponse->addHeader('Access-Control-Allow-Origin: *');
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
                    $this->HTTPResponse->setCacheHeader(500);
                    $this->HTTPResponse->addHeader('Access-Control-Allow-Origin: *');
                    return $this->renderJSON($success);
                }
            }
        endif;

        // DELETE
        if ($this->HTTPRequest->method() === 'DELETE' && $postId && $post && $user && $user->havePostRights($post)) :
            $success = $postManager->deletePost($postId);

            if ($success) {
                $this->HTTPResponse->addHeader('Access-Control-Allow-Origin: *');
                return $this->renderJSON([
                    "status" => 1,
                    "message" => 'Post deleted'
                ]);
            }
        endif;

        // If something goes wrong :
        $this->HTTPResponse->unauthorized([
            'Authentication' => "Basic",
            "Needed arguments" => ['title', 'content']
        ]);
    }
}
