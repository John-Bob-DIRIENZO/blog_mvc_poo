<?php


namespace Entity;


use Model\UserManager;

class Post
{
    private $id;
    private $date;
    private $title;
    private $content;
    private $author;

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
    public function getDate()
    {
        return new \DateTime($this->date);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        $manager = new UserManager();
        return $manager->getUserById($this->author);
    }
}