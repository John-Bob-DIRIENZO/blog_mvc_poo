<?php


namespace Controller;


use Entity\Comment;
use Model\CommentManager;

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
}