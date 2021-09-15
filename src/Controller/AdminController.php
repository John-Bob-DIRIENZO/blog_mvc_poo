<?php


namespace Controller;


use Entity\Comment;
use Entity\Post;
use Model\CommentManager;
use Model\PostManager;
use Model\UserManager;

class AdminController extends BaseController
{
    /**
     * Shows the main admin page if connected
     * @return mixed
     */
    public function executeIndex()
    {
        if (SecurityController::isAuthenticated()) {
            return $this->render('Zone Admin', [], 'Admin/index');
        }

        $this->HTTPResponse->redirect('/login');
    }

    /**
     * Shows the user list if super admin
     * @return mixed
     */
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