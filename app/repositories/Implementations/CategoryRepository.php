<?php

require_once __DIR__ . "/../Interfaces/IGenericRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class CategoryRepository implements IGenericRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    // LIST (Paging + Search)
    public function list($input)
    {
        $page = $input['page'] ?? 1;
        $pageSize = $input['pageSize'] ?? 10;
        $search = trim($input['searchValue'] ?? '');

        $offset = ($page - 1) * $pageSize;

        $result = [
            "RowCount" => 0,
            "DataItems" => [],
            "Pages" => []
        ];

        // COUNT
        $sql = "SELECT COUNT(*) FROM Categories
            WHERE (:search = '' OR CategoryName LIKE :searchLike)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => $search,
            "searchLike" => "%$search%"
        ]);

        $result["RowCount"] = $stmt->fetchColumn();

        if ($result["RowCount"] == 0) return $result;

        // DATA
        $sql = "SELECT * FROM Categories
        WHERE (:search = '' OR CategoryName LIKE :searchLike)
        ORDER BY CategoryName
        LIMIT $offset, $pageSize";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => $search,
            "searchLike" => "%$search%"
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
        $sql = "SELECT * FROM Categories WHERE CategoryID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data)
    {
        $sql = "INSERT INTO Categories (CategoryName, Description)
            VALUES (:name, :desc)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "name" => $data['CategoryName'],
            "desc" => $data['Description']
        ]);

        return $this->conn->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE Categories
            SET CategoryName = :name,
                Description = :desc
            WHERE CategoryID = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name" => $data['CategoryName'],
            "desc" => $data['Description'],
            "id" => $data['CategoryID']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Categories WHERE CategoryID = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute(["id" => $id]);
    }

    public function isUsed($id)
    {
        $sql = "SELECT COUNT(*) FROM Products WHERE CategoryID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);

        return $stmt->fetchColumn() > 0;
    }
}
