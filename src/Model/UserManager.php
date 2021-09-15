<?php


namespace Model;


use Entity\User;

class UserManager extends BaseManager
{
    /**
     * @return array
     */
    public function getAllUsers(): array
    {
        $query = $this->db->query('SELECT * FROM users');
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\User');
        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @return User|bool
     */
    public function getUserById(int $id)
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\User');
        return $query->fetch();
    }

    /**
     * @param string $email
     * @return User|bool
     */
    public function getUserByEmail(string $email = null)
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $query->bindValue(':email', $email, \PDO::PARAM_STR);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\User');
        return $query->fetch();
    }

    /**
     * Checks if user exists in DB
     * @param string $email
     * @return bool
     */
    public function userExists(string $email): bool
    {
        return $this->getUserByEmail($email) instanceof User;
    }

    /**
     * Checks if a users exists in DB
     * and its password matches the one in DB
     * @param User $user
     * @return bool
     */
    public function userMatches(User $user): bool
    {
        return $this->getUserByEmail($user->getEmail())->getPassword() === $user->getPassword();
    }

    /**
     * @param null $login
     * @param null $password
     * @return User|bool
     */
    public function checkCredentials($login = null, $password = null)
    {
        if ( !is_string($login) || !is_string($password)) {
            return false;
        }

        $manager = new UserManager();
        $user = $manager->getUserByEmail($login);

        if ($user !== false && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return false;
    }

    /**
     * @param User $newUser
     * @return User
     */
    public function addUser(User $newUser): User
    {
        $insert = $this->db->prepare('INSERT INTO users (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)');
        $insert->bindValue(':firstName', $newUser->getFirstName(), \PDO::PARAM_STR);
        $insert->bindValue(':lastName', $newUser->getLastName(), \PDO::PARAM_STR);
        $insert->bindValue(':email', $newUser->getEmail(), \PDO::PARAM_STR);
        $insert->bindValue(':password', $newUser->getPassword(), \PDO::PARAM_STR);
        $insert->execute();

        return $this->getUserByEmail($newUser->getEmail());
    }

    /**
     * @param User $user
     * @return User
     */
    public function updateUser(User $user): User
    {
        $update = $this->db->prepare('UPDATE users SET firstName = :firstName, lastName = :lastName, password = :password, roles = :roles WHERE email = :email');
        $update->bindValue(':firstName', htmlspecialchars($user->getFirstName()), \PDO::PARAM_STR);
        $update->bindValue(':lastName', htmlspecialchars($user->getLastName()), \PDO::PARAM_STR);
        $update->bindValue(':email', htmlspecialchars($user->getEmail()), \PDO::PARAM_STR);
        $update->bindValue(':password', htmlspecialchars($user->getPassword()), \PDO::PARAM_STR);
        $update->bindValue(':roles', $user->getRoles(), \PDO::PARAM_STR);
        $update->execute();

        return $this->getUserByEmail($user->getEmail());
    }

    /**
     * Deletes an user and all of its posts and comments
     * @param string $email
     * @return bool
     */
    public function deleteUserByEmail(string $email): bool
    {
        $delete = $this->db->prepare('DELETE FROM users WHERE email = :email');
        $delete->bindValue(':email', $email, \PDO::PARAM_STR);

        $commentManager = new CommentManager();
        $commentManager->deleteCommentsByAuthorId($this->getUserByEmail($email)->getId());

        $postManager = new PostManager();
        $postManager->deletePostsByAuthorId($this->getUserByEmail($email)->getId());

        return $delete->execute();
    }

    /**
     * Deletes an user and all of its posts and comments
     * @param int $id
     * @return bool
     */
    public function deleteUserById(int $id): bool
    {
        $delete = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $delete->bindValue(':id', $id, \PDO::PARAM_INT);

        $commentManager = new CommentManager();
        $commentManager->deleteCommentsByAuthorId($id);

        $postManager = new PostManager();
        $postManager->deletePostsByAuthorId($id);

        return $delete->execute();
    }
}