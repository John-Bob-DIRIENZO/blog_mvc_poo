<?php


namespace Entity;


class Image extends BaseEntity
{
    private $id;
    private $basePath = '/Public/Images/';
    private $name;
    private $absUrl;

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
        if ($this->absUrl) {
            return $this->absUrl;
        }
        $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__DIR__) . $this->basePath) . $this->name;
    }

    /**
     * @return mixed
     */
    public function getAbsUrl()
    {
        return $this->absUrl;
    }

    /**
     * @param mixed $absUrl
     */
    public function setAbsUrl($absUrl): void
    {
        $this->absUrl = $absUrl;
    }
}