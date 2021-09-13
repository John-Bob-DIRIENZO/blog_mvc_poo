<?php


namespace Controller;


use Entity\Comment;
use Entity\Post;
use Model\CommentManager;
use Model\PostManager;

class AdminController extends BaseController
{
    public function executeIndex()
    {
        return$this->render('Zone Admin', [], 'Admin/index');
    }

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

            header('Location: /article/' . $_POST['postId']);
            exit();
        }

        header('Location: /article/' . $_POST['postId']);
        exit();
    }

    public function executeWritePost()
    {
        if (SecurityController::isAuthenticated() && !isset($_POST['title']) && !isset($_POST['content'])) {
            return $this->render('Write new article', [], 'Admin/write-article');
        }
        elseif (SecurityController::isAuthenticated() && isset($_POST['title']) && isset($_POST['content'])) {
            $newPost = new Post(array(
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'authorId' => SecurityController::getLoggedUser()->getId()
            ));

            $manager = new PostManager();
            $newPost = $manager->addPost($newPost);

            header('Location: /article/' . $newPost->getId());
            exit();
        }
        else {
            header('Location: /');
            exit();
        }

    }
}