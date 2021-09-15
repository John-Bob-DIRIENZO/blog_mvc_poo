<?php


namespace Entity;


use Model\CommentManager;
use Model\ImageManager;
use Model\UserManager;

class Post extends BaseEntity
{
    private $id;
    private $date;
    private $title;
    private $content;
    private $authorId;
    private $imageId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
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
        return $manager->getUserById($this->authorId);
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param mixed $authorId
     */
    public function setAuthorId($authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        $manager = new CommentManager();
        return $manager->getCommentsByPostId($this->id);
    }

    /**
     * @return mixed
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * @param mixed $imageId
     */
    public function setImageId($imageId): void
    {
        $this->imageId = $imageId;
    }


    public function hasImage(): bool
    {
        return $this->imageId !== null;
    }

    public function getImageUrl()
    {
        $manager = new ImageManager();
        $image = $manager->getImageById($this->imageId);
        $url = $image ? $image->buildUrl() : null;

        return $url;
    }
}