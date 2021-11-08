<?php


namespace Model;

use Vendor\Core\PDOFactory;

abstract class BaseManager
{
    protected $db;

    public function __construct()
    {
        $this->db = PDOFactory::getMysqlConnexion();
    }
}