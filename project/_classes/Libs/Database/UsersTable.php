<?php

namespace Libs\Database;

use PDO;
use PDOException;

class UsersTable
{
    private PDO $db;

    public function __construct(MySQL $mysql)
    {
        $this->db = $mysql->connect();
    }

    public function all()
    {
        $result = $this->db->query("SELECT * FROM users");
        return $result->fetchAll();
    }

    public function find(string $email, string $password)
    {
        try {
            $statement = $this->db->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
            $statement->execute(["email" => $email, "password" => $password]);

            return $statement->fetch();

        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }

    public function insert(Array $data)
    {
        try {
            $statement = $this->db->prepare(
                "INSERT INTO users (name, email, phone, address,
                password, created_at) VALUES (:name, :email,
                :phone, :address, :password, NOW())"
            );

            $statement->execute($data);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }
}
