<?php

class UserModel
{
    private PDO $connection;

    public function __construct(PDO $database)
    {
        $this->connection = $database;
    }

    public function getUserByEmail(string $email)
    {

        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch();
    }

    public function createUser(string $fullname, string $email, string $password)
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
