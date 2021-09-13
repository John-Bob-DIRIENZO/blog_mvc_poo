<?php


namespace Controller;


use Model\UserManager;

class AdminController extends BaseController
{
    public function executeIndex()
    {
        $manager = new UserManager();
        return var_dump(SecurityController::isAuthenticated());
    }
}