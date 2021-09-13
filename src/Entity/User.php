<?php


namespace Entity;


use Model\CommentManager;
use Model\PostManager;

class User extends BaseEntity
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $roles; // Should always be serialized here

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Return an array of roles with
     * at least ROLE_USER
     * @return array
     */
    public function checkRoles(): array
    {
        $roles = unserialize($this->roles);
        $roles[] .= 'ROLE_USER';
        return $roles;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->checkRoles());
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = serialize($roles);
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function getPosts(): array
    {
        $manager = new PostManager();
        return $manager->getPostsByAuthorId($this->id);
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        $manager = new CommentManager();
        return $manager->getCommentsByAuthorId($this->id);
    }

    /**
     * @param bool $admin
     * @return bool
     */
    public function setAdmin($admin): bool
    {
        if ($admin) {
            $roles = unserialize($this->roles);
            $roles[] .= 'ROLE_ADMIN';
            $this->setRoles($roles);
            return true;
        }

        $this->setRoles([]);
        return false;
    }

    public function havePostRights(Post $post): bool
    {
        return $post->getAuthorId() === $this->id || $this->isAdmin();
    }

    public function haveCommentRights(Comment $comment): bool
    {
        return $comment->getAuthorId() === $this->id || $this->isAdmin();
    }
}