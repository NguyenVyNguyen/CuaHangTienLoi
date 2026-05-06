<?php

require_once __DIR__ . "/../../database/Database.php";

class ProvinceRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function list()
    {
        $sql = "
            SELECT ProvinceName
            FROM Provinces
            ORDER BY ProvinceName
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}