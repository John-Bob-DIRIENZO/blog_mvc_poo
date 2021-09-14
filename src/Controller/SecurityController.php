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

    public function executeSignup()
    {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            return $this->render('Create a new user', [], 'Security/signup');
        }

        $manager = new UserManager();

        if (!$manager->userExists($_POST['email']) && ($_POST['password'] === $_POST['password_check'])) {
            $newUser = new User(array(
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ));

            $this->logUser($manager->addUser($newUser));

            header('Location: /');
            exit();
        }
        elseif ($manager->userExists($_POST['email'])) {
            Flash::setFlash('The user already exists');
            header('Location: /signup');
            exit();
        }
        elseif ($_POST['password'] !== $_POST['password_check']) {
            Flash::setFlash('Passwords are not identical');
            header('Location: /signup');
            exit();
        }
        else {
            Flash::setFlash('Unknown error');
            header('Location: /signup');
            exit();
        }
    }

    public function executeUpdateUser()
    {
        if (self::isAuthenticated() && isset($_POST['userFirstName']) && isset($_POST['userLastName']) && ($_POST['userPassword'] === $_POST['userCheckPassword'])) {
            $updatedUser = new User(array(
                'firstName' => $_POST['userFirstName'],
                'lastName' => $_POST['userLastName'],
                'email' => self::getLoggedUser()->getEmail(),
                'admin' => $_POST['userRole'],
                'password' => $_POST['userPassword']
            ));

            $manager = new UserManager();
            $this->logUser($manager->updateUser($updatedUser));

            header('Location: /admin');
            exit();
        }
        elseif (self::isAuthenticated() && isset($_POST['userFirstName']) && isset($_POST['userLastName']) && ($_POST['userPassword'] !== $_POST['userCheckPassword'])) {
            Flash::setFlash('Passwords are not identical');
            header('Location: /admin');
            exit();
        }
        else {
            Flash::setFlash('Unknown error');
            header('Location: /');
            exit();
        }
    }

    public function executeDeleteUser()
    {
        if (SecurityController::isAuthenticated() && SecurityController::getLoggedUser()->isAdmin() && SecurityController::getLoggedUser()->getId() != $this->params['id']) {
            $userManager = new UserManager();
            $userManager->deleteUserById($this->params['id']);
        }

        if (SecurityController::getLoggedUser()->getId() == $this->params['id']) {
            Flash::setFlash('Please don\'t delete yourself, you\'ll break everything !');
        }

        header('Location: /userlist');
        exit();
    }

    /**
     * Verify if there is a logged user
     * and if it's legit
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        if (isset($_SESSION['logged_user'])) {
            $manager = new UserManager();
            return $manager->userMatches(unserialize($_SESSION['logged_user']));
        }

        return false;
    }

    /**
     * @return User|null
     */
    public static function getLoggedUser()
    {
        if (isset($_SESSION['logged_user'])) {
            return unserialize($_SESSION['logged_user']);
        }

        return null;
    }
}