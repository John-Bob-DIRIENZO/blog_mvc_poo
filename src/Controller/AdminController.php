<?php


namespace Controller;


use Entity\Comment;
use Entity\Post;
use Model\CommentManager;
use Model\PostManager;
use Model\UserManager;

class AdminController extends BaseController
{
    public function executeIndex()
    {
        if (SecurityController::isAuthenticated()) {
            return $this->render('Zone Admin', [], 'Admin/index');
        }

        $this->HTTPResponse->redirect('/login');
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
        }

        $this->HTTPResponse->redirect('/article/' . $_POST['postId']);
    }

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

            $manager = new PostManager();
            $newPost = $manager->addPost($newPost);

            $this->HTTPResponse->redirect('/article/' . $newPost->getId());
        }

        $this->HTTPResponse->redirect('/');
    }

    public function executeDeletePost(): void
    {
        $postManager = new PostManager();
        $post = $postManager->getPostById($this->params['id']);

        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($post)) {
            $postManager->deletePost($this->params['id']);
        }

        $this->HTTPResponse->redirect('/');
    }

    public function executeUpdatePost()
    {
        $manager = new PostManager();
        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($manager->getPostById($this->params['id'])) && !isset($_POST['title'])) {
            return $this->render('Update article', [
                'article' => $manager->getPostById($this->params['id'])
            ], 'Admin/update-article');
        } elseif (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($manager->getPostById($this->params['id'])) && isset($_POST['title']) && isset($_POST['content'])) {
            $newPost = new Post(array(
                'id' => $this->params['id'],
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ));

            $manager->updatePost($newPost);
        }

        $this->HTTPResponse->redirect('/article/' . $this->params['id']);
    }

    public function executeUserlist()
    {
        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->isAdmin()) {
            $userManager = new UserManager();
            return $this->render('User list', [
                'users' => $userManager->getAllUsers()
            ], 'Admin/userlist');
        }

        $this->HTTPResponse->redirect('/');
    }
}