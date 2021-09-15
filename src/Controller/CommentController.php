<?php


namespace Controller;


use Entity\Comment;
use Model\CommentManager;
use Model\PostManager;
use Model\UserManager;

class CommentController extends BaseController
{
    /**
     * Checks authentication and writes comment in DB
     */
    public function executePostComment(): void
    {
        if (SecurityController::isAuthenticated() && isset($_POST['content'])) {
            $comment = new Comment(array(
                'postId' => $_POST['postId'],
                'authorId' => SecurityController::getLoggedUser()->getId(),
                'content' => $_POST['content']
            ));
            $controller = new CommentManager();
            $controller->addComment($comment);
        }

        $this->HTTPResponse->redirect('/article/' . $_POST['postId']);
    }

    /**
     * Checks authentication and deletes comment from DB
     */
    public function executeDeleteComment(): void
    {
        $commentManager = new CommentManager();
        $comment = $commentManager->getCommentById($this->params['id']);
        $postId = $comment->getPostId();

        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->haveCommentRights($comment)) {
            $commentManager->deleteCommentById($this->params['id']);
        }

        $this->HTTPResponse->redirect('/article/' . $postId);
    }

    public function executeCommentsApi()
    {
        $postManager = new PostManager();
        $userManager = new UserManager();
        $commentManager = new CommentManager();

        $user = $userManager->checkCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        $postId = !empty($this->params['mainId']) ? $this->params['mainId'] : false;
        $post = $postManager->postExists($postId) ? $postManager->getPostById($postId) : false;
        $commentId = !empty($this->params['id']) ? $this->params['id'] : false;
        $comment = $commentManager->commentExists($commentId) ? $commentManager->getCommentById($commentId) : false;

        parse_str(file_get_contents('php://input'), $_PUT);

        // GET
        if ($this->HTTPRequest->method() === 'GET') :
            if ($post) {
                switch ($commentId) {
                    case false:
                        $this->HTTPResponse->setCacheHeader(300);
                        return $this->renderJSON($commentManager->getCommentsByPostId($postId, true));

                    case true:
                        $comment = $commentManager->getCommentById($commentId, true);
                        if (empty($comment)) {
                            return new ErrorController('noRouteJSON');
                        }
                        $this->HTTPResponse->setCacheHeader(300);
                        return $this->renderJSON($comment);
                }
            }
            switch ($commentId) {
                case false:
                    $this->HTTPResponse->setCacheHeader(300);
                    return $this->renderJSON($commentManager->getAllComments(true));

                case true:
                    $comment = $commentManager->getCommentById($commentId, true);
                    if (empty($comment)) {
                        return new ErrorController('noRouteJSON');
                    }
                    $this->HTTPResponse->setCacheHeader(300);
                    return $this->renderJSON($comment);
            }
        endif;

        // POST
        if ($this->HTTPRequest->method() === 'POST' && !$commentId && $post) :

            if ($user && !empty($_POST['content'])) {
                $newComment = new Comment(array(
                    'postId' => $postId,
                    'content' => $_POST['content'],
                    'authorId' => $user->getId()
                ));
                $success = $commentManager->addComment($newComment, true);

                if ($success) {
                    $this->HTTPResponse->setCacheHeader(300);
                    return $this->renderJSON($success);
                }
            }
        endif;

        // Comme je n'ai qu'un élément dans le commentaire, PUT et PATCH deviennent identiques
        if (($this->HTTPRequest->method() === 'PUT' || $this->HTTPRequest->method() === 'PATCH') && $comment) :

            if ($user && !empty($_PUT['content']) && $user->haveCommentRights($comment)) {
                $comment->setContent($_PUT['content']);
                $success = $commentManager->updateComment($comment, true);

                if ($success) {
                    $this->HTTPResponse->setCacheHeader(300);
                    return $this->renderJSON($success);
                }
            }
        endif;

        // DELETE
        if ($this->HTTPRequest->method() === 'DELETE' && $comment && $user && $user->haveCommentRights($comment)) :
            $success = $commentManager->deleteCommentById($commentId);

            if ($success) {
                return $this->renderJSON([
                    "status" => 1,
                    "message" => 'Post deleted'
                ]);
            }
        endif;

        // If something goes wrong :
        $this->HTTPResponse->unauthorized([
            'Authentication' => "Basic",
            "Needed arguments" => ['content']
        ]);
    }

}