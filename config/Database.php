<?php

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}


    public static function getConnection(): PDO
    {
        if (self::$instance == null) {
            $dsn = "mysql:host=127.0.0.1;dbname=catering_db;charset=utf8mb4";
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
}
