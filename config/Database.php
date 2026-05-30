<?php

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}


    public static function getConnection(): PDO
    {
        if (self::$instance == null) {
            $dsn = "mysql:host=127.1.1.0;dbname=catering_db;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, 'root', '', $options);
            } catch (\PDOException $e) {
                die("Veri tabanına bağlanılamadı: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    // private $host = "127.0.0.1";
    // private $dbname = "catering_db";
    // private $username = "root";
    // private $password = "";
    // private $charset = "utf8mb4";

    // private ?PDO $pdo;


    // public function getConnection()
    // {
    //     $this->pdo = null;

    //     try {
    //         $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
    //         $options = [

    //             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //             PDO::ATTR_EMULATE_PREPARES => false
    //         ];
    //         $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
    //     } catch (\PDOException $e) {
    //         die("Veritabanına bağlanılamadı: " . $e->getMessage());
    //     }
    //     return $this->pdo;
    // }
}
