<?php

require_once __DIR__ . "/../database/Database.php";

class ReportService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getDashboardData()
    {
        return [
            "totalProducts"   => $this->count("Products"),
            "totalCategories" => $this->count("Categories"),
            "totalSuppliers"  => $this->count("Suppliers"),
            "totalOrders"     => $this->count("Orders"),
            "totalCustomers"  => $this->count("Customers"),
        ];
    }

    private function count($table)
    {
        $sql = "SELECT COUNT(*) as total FROM $table";
        $stmt = $this->db->getConnection()->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["total"] ?? 0;
    }
}