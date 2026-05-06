<?php

require_once __DIR__ . "/../Interfaces/IGenericRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class ShipperRepository implements IGenericRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function list($input)
    {
        $page = isset($input['page']) ? (int)$input['page'] : 1;
        $pageSize = isset($input['pageSize']) ? (int)$input['pageSize'] : 10;
        $search = isset($input['searchValue']) ? trim($input['searchValue']) : '';

        $offset = ($page - 1) * $pageSize;

        $result = [
            "RowCount" => 0,
            "DataItems" => [],
            "Pages" => []
        ];

        $searchLike = "%$search%";

        // 🔥 COUNT
        $countSql = "
        SELECT COUNT(*) FROM Shippers
        WHERE (:search = '' 
            OR ShipperName LIKE :searchLike 
            OR Phone LIKE :searchLike)
    ";

        $stmt = $this->conn->prepare($countSql);
        $stmt->execute([
            "search" => $search,
            "searchLike" => $searchLike
        ]);

        $result["RowCount"] = $stmt->fetchColumn();

        if ($result["RowCount"] == 0) return $result;

        // 🔥 DATA
        $sql = "
        SELECT * FROM Shippers
        WHERE (:search = '' 
            OR ShipperName LIKE :searchLike 
            OR Phone LIKE :searchLike)
        ORDER BY ShipperName
        LIMIT :offset, :pageSize
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":search", $search);
        $stmt->bindValue(":searchLike", $searchLike);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":pageSize", $pageSize, PDO::PARAM_INT);

        $stmt->execute();

        $result["DataItems"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔥 PAGING + "..."
        $totalPages = ceil($result["RowCount"] / $pageSize);

        $start = max(1, $page - 2);
        $end   = min($totalPages, $page + 2);

        // 👉 Trang đầu
        if ($start > 1) {
            $result["Pages"][] = ["Page" => 1, "IsCurrent" => false];

            if ($start > 2) {
                $result["Pages"][] = ["Page" => 0]; // ...
            }
        }

        // 👉 Các trang giữa
        for ($i = $start; $i <= $end; $i++) {
            $result["Pages"][] = [
                "Page" => $i,
                "IsCurrent" => ($i == $page)
            ];
        }

        // 👉 Trang cuối
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $result["Pages"][] = ["Page" => 0]; // ...
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
        $stmt = $this->conn->prepare("SELECT * FROM Shippers WHERE ShipperID = ?");
        $stmt->execute([$id]);
        // SỬA: Trả về FETCH_ASSOC
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data)
    {
        $sql = "INSERT INTO Shippers (ShipperName, Phone)
                VALUES (:name, :phone)";

        $stmt = $this->conn->prepare($sql);

        // SỬA: Truy xuất dữ liệu mảng
        $stmt->execute([
            "name" => $data['ShipperName'],
            "phone" => $data['Phone']
        ]);

        return $this->conn->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE Shippers
                SET ShipperName = :name,
                    Phone = :phone
                WHERE ShipperID = :id";

        $stmt = $this->conn->prepare($sql);

        // SỬA: Truy xuất dữ liệu mảng
        return $stmt->execute([
            "name" => $data['ShipperName'],
            "phone" => $data['Phone'],
            "id" => $data['ShipperID']
        ]);
    }
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Shippers WHERE ShipperID = ?");
        return $stmt->execute([$id]);
    }

    public function isUsed($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Orders WHERE ShipperID = ?");
        $stmt->execute([$id]);

        return $stmt->fetchColumn() > 0;
    }
}
