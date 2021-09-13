<?php


namespace Model;


use Entity\User;

class UserManager extends BaseManager
{
    public function getUserById(int $id)
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\User');
        return $query->fetch();
    }

    public function getUserByEmail(string $email)
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $query->bindValue(':email', $email, \PDO::PARAM_STR);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\User');
        return $query->fetch();
    }
}