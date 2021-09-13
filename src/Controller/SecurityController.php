<?php


namespace Controller;


use Entity\User;
use Model\UserManager;
use Vendor\Core\Flash;

class SecurityController extends BaseController
{
    public function executeLogin()
    {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            return $this->render('Please LogIn', [], 'Security/login');
        }

        $manager = new UserManager();
        $user = $manager->getUserByEmail($_POST['email']);

        if ($user !== false && password_verify($_POST['password'], $user->getPassword())) {
            $this->logUser($user);
            header('Location: /admin');
            exit();
        }
        elseif ($user !== false && !password_verify($_POST['password'], $user->getPassword())) {
            Flash::setFlash('Wrong Password');
            header('Location: /login');
            exit();
        }
        else {
            Flash::setFlash('No User Found');
            header('Location: /login');
            exit();
        }

    }

    /**
     * @param User $user
     */
    private function logUser(User $user): void
    {
        $_SESSION['logged_user'] = serialize($user);
    }

    public function executeLogout()
    {
        session_destroy();
        header('Location: /');
        exit();
    }
}