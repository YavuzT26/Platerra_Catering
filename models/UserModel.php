<?php

class UserModel
{
    private $connection;

    public function __construct($database)
    {
        $this->connection = $database;
    }

    public function getUserByEmail($email)
    {

        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch();
    }

    public function CreateUser($fullname, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO 
                users (full_name,email,password)
              VALUES
                (:name,:email,:password)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':name' => $fullname,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);
    }
}
