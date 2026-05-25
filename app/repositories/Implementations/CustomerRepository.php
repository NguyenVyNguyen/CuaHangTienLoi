<?php

require_once __DIR__ . "/../Interfaces/ICustomerRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class CustomerRepository implements ICustomerRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    // LIST
    public function list($input)
    {
        $page = $input["page"] ?? 1;
        $pageSize = $input["pageSize"] ?? 10;
        $searchValue = $input["searchValue"] ?? "";

        $offset = ($page - 1) * $pageSize;

        $result = [
            "RowCount" => 0,
            "DataItems" => [],
            "Pages" => []
        ];

        // COUNT
        $sql = "SELECT COUNT(*) FROM Customers
            WHERE (:search = '' 
                OR CustomerName LIKE :searchLike
                OR ContactName LIKE :searchLike
                OR Phone LIKE :searchLike)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => $searchValue,
            "searchLike" => "%$searchValue%"
        ]);

        $result["RowCount"] = $stmt->fetchColumn();

        if ($result["RowCount"] == 0) return $result;

        // DATA
        $sql = "SELECT * FROM Customers
            WHERE (:search = '' 
                OR CustomerName LIKE :searchLike
                OR ContactName LIKE :searchLike
                OR Phone LIKE :searchLike)
            ORDER BY CustomerName
            LIMIT $offset, $pageSize";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => $searchValue,
            "searchLike" => "%$searchValue%"
        ]);

        $result["DataItems"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // PAGING
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
        $sql = "SELECT * FROM Customers WHERE CustomerID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);

        // KHUYÊN DÙNG: Đổi thành FETCH_ASSOC nếu file edit.php của bạn dùng $model['Name']
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data)
    {
        $sql = "INSERT INTO Customers 
                (CustomerName, ContactName, Province, Address, Phone, Email, IsLocked) 
                VALUES 
                (:name, :contact, :province, :address, :phone, :email, :locked)";

        $stmt = $this->conn->prepare($sql);

        // Sửa truy xuất từ -> thành []
        $stmt->execute([
            "name" => $data['CustomerName'],
            "contact" => $data['ContactName'],
            "province" => $data['Province'],
            "address" => $data['Address'],
            "phone" => $data['Phone'],
            "email" => $data['Email'],
            "locked" => (!empty($data['IsLocked'])) ? 1 : 0
        ]);

        return $this->conn->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE Customers SET 
                CustomerName = :name, 
                ContactName = :contact, 
                Province = :province, 
                Address = :address, 
                Phone = :phone, 
                Email = :email, 
                IsLocked = :locked 
                WHERE CustomerID = :id";

        $stmt = $this->conn->prepare($sql);

        // Sửa truy xuất từ -> thành []
        return $stmt->execute([
            "name" => $data['CustomerName'],
            "contact" => $data['ContactName'],
            "province" => $data['Province'],
            "address" => $data['Address'],
            "phone" => $data['Phone'],
            "email" => $data['Email'],
            "locked" => (!empty($data['IsLocked'])) ? 1 : 0,
            "id" => $data['CustomerID']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Customers WHERE CustomerID = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute(["id" => $id]);
    }

    public function isUsed($id)
    {
        $sql = "SELECT COUNT(*) FROM Orders WHERE CustomerID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function validateEmail($email, $id = 0)
    {
        $sql = "SELECT COUNT(*) FROM Customers WHERE Email = :email AND CustomerID <> :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "email" => $email,
            "id" => $id
        ]);

        return (int)$stmt->fetchColumn() == 0;
    }

    public function updatePassword($id, $hashedPassword)
    {
        $sql = "UPDATE Customers 
                SET Password = :password 
                WHERE CustomerID = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'password' => $hashedPassword
        ]);
    }
}
