<?php

require_once __DIR__ . "/../Interfaces/IGenericRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class SupplierRepository implements IGenericRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function list($input)
    {
        $page = isset($input['page']) ? (int)$input['page'] : 1;
        $pageSize = isset($input['pageSize']) ? (int)$input['pageSize'] : 20;
        $search = isset($input['searchValue']) ? trim($input['searchValue']) : '';

        $offset = ($page - 1) * $pageSize;

        $result = [
            "RowCount" => 0,
            "DataItems" => [],
            "Pages" => []
        ];

        $searchLike = "%$search%";

        // 🔥 COUNT
        $countSql = "SELECT COUNT(*) FROM Suppliers
        WHERE (:search = '' 
            OR SupplierName LIKE :searchLike
            OR ContactName LIKE :searchLike
            OR Phone LIKE :searchLike)";

        $stmt = $this->conn->prepare($countSql);
        $stmt->execute([
            "search" => $search,
            "searchLike" => $searchLike
        ]);

        $result["RowCount"] = $stmt->fetchColumn();

        if ($result["RowCount"] == 0) return $result;

        // 🔥 DATA
        $sql = "SELECT * FROM Suppliers
        WHERE (:search = '' 
            OR SupplierName LIKE :searchLike
            OR ContactName LIKE :searchLike
            OR Phone LIKE :searchLike)
        ORDER BY SupplierName
        LIMIT :offset, :pageSize";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":search", $search);
        $stmt->bindValue(":searchLike", $searchLike);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":pageSize", $pageSize, PDO::PARAM_INT);

        $stmt->execute();

        $result["DataItems"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //PAGING
        $totalPages = ceil($result["RowCount"] / $pageSize);

        $start = max(1, $page - 2);
        $end   = min($totalPages, $page + 10);

        if ($start > 1) {
            $result["Pages"][] = [
                "Page" => 1,
                "IsCurrent" => false
            ];

            if ($start > 2) {
                $result["Pages"][] = [
                    "Page" => 0 
                ];
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $result["Pages"][] = [
                "Page" => $i,
                "IsCurrent" => ($i == $page)
            ];
        }

        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $result["Pages"][] = [
                    "Page" => 0 
                ];
            }

            $result["Pages"][] = [
                "Page" => $totalPages,
                "IsCurrent" => false
            ];
        }
        return $result;
    }

    public function get($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Suppliers WHERE SupplierID = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data)
    {
        $sql = "INSERT INTO Suppliers
                (SupplierName, ContactName, Province, Address, Phone, Email)
                VALUES (:name, :contact, :province, :address, :phone, :email)";

        $stmt = $this->conn->prepare($sql);

        // Chuyển toàn bộ $data->property thành $data['key']
        $stmt->execute([
            "name" => $data['SupplierName'],
            "contact" => $data['ContactName'],
            "province" => $data['Province'],
            "address" => $data['Address'],
            "phone" => $data['Phone'],
            "email" => $data['Email']
        ]);

        return $this->conn->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE Suppliers SET
                SupplierName = :name,
                ContactName = :contact,
                Province = :province,
                Address = :address,
                Phone = :phone,
                Email = :email
                WHERE SupplierID = :id";

        $stmt = $this->conn->prepare($sql);

        // Chuyển toàn bộ $data->property thành $data['key']
        return $stmt->execute([
            "name" => $data['SupplierName'],
            "contact" => $data['ContactName'],
            "province" => $data['Province'],
            "address" => $data['Address'],
            "phone" => $data['Phone'],
            "email" => $data['Email'],
            "id" => $data['SupplierID']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Suppliers WHERE SupplierID = ?");
        return $stmt->execute([$id]);
    }

    public function isUsed($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Products WHERE SupplierID = ?");
        $stmt->execute([$id]);

        return $stmt->fetchColumn() > 0;
    }
}
