<?php


namespace Vendor\Core;


class PDOFactory
{
    public static function getMysqlConnexion(): \PDO
    {
        try {
            $db = new \PDO('mysql:host=db;dbname=cours_mvc', 'root', 'example');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            ob_start();
            ?>
            <h1>Rien de méchant, juste une erreur...</h1>
            <p><?= $e->getMessage(); ?></p>
            <?php
            echo ob_get_clean();
        }

        return $db;
    }
}