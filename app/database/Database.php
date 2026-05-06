<?php

class Database
{
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $db   = "LiteCommerceDB";
    private $user = "root";
    private $pass = "123456";
    private $charset = "utf8mb4";

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";

        $this->conn = new PDO($dsn, $this->user, $this->pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}