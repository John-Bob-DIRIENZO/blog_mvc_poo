<?php


namespace Entity;


class Image extends BaseEntity
{
    private $id;
    private $basePath = '/Public/Images/';
    private $name;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function buildUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__DIR__) . $this->basePath) . $this->name;
    }
}