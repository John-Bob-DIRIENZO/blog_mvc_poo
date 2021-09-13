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
        header('Location: /');
        exit();
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

    public function executeDeleteComment(): void
    {
        $commentManager = new CommentManager();
        $comment = $commentManager->getCommentById($this->id);
        $postId = $comment->getPostId();

        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->haveCommentRights($comment)) {
            $commentManager->deleteCommentById($this->id);
        }

        header('Location: /article/' . $postId);
        exit();
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

            header('Location: /article/' . $newPost->getId());
            exit();
        } else {
            header('Location: /');
            exit();
        }

    }

    public function executeDeletePost(): void
    {
        $postManager = new PostManager();
        $post = $postManager->getPostById($this->id);

        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($post)) {
            $postManager->deletePost($this->id);
        }

        header('Location: /');
        exit();
    }

    public function executeUpdatePost()
    {
        $manager = new PostManager();
        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($manager->getPostById($this->id)) && !isset($_POST['title'])) {
            return $this->render('Update article', [
                'article' => $manager->getPostById($this->id)
            ], 'Admin/update-article');
        } elseif (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->havePostRights($manager->getPostById($this->id)) && isset($_POST['title']) && isset($_POST['content'])) {
            $newPost = new Post(array(
                'id' => $this->id,
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ));

            $manager->updatePost($newPost);
        }
        header('Location: /article/' . $this->id);
        exit();
    }

    public function executeUserlist()
    {
        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->isAdmin()) {
            $userManager = new UserManager();
            return $this->render('User list', [
                'users' => $userManager->getAllUsers()
            ], 'Admin/userlist');
        }

        header('Location: /');
        exit();
    }
}