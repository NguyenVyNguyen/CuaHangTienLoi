<?php

require_once __DIR__ . "/../Interfaces/IGenericRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class PromotionRepository implements IGenericRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function list($input)
    {
        $page = $input['page'];
        $pageSize = $input['pageSize'];
        $search = $input['searchValue'];

        $offset = ($page - 1) * $pageSize;

        $result = [
            "RowCount" => 0,
            "DataItems" => [],
            "Pages" => []
        ];

        // COUNT
        $sql = "SELECT COUNT(*) FROM Promotions
                WHERE (:search = '' OR PromotionName LIKE :searchLike)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => $search,
            "searchLike" => "%$search%"
        ]);

        $result["RowCount"] = $stmt->fetchColumn();

        if ($result["RowCount"] == 0) return $result;

        // DATA
        $sql = "SELECT * FROM Promotions
                WHERE (:search = '' OR PromotionName LIKE :searchLike)
                ORDER BY PromotionName
                LIMIT $offset, $pageSize";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => $search,
            "searchLike" => "%$search%"
        ]);

        $result["DataItems"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // PAGING (CÓ ...)
        $totalPages = ceil($result["RowCount"] / $pageSize);

        $start = max(1, $page - 2);
        $end   = min($totalPages, $page + 2);

        if ($start > 1) {
            $result["Pages"][] = ["Page" => 1, "IsCurrent" => false];

            if ($start > 2) {
                $result["Pages"][] = ["Page" => 0]; // ...
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
        $stmt = $this->conn->prepare("SELECT * FROM Promotions WHERE PromotionID = :id");
        $stmt->execute(["id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTargets($promotionID)
    {
        // lấy product
        $sql = "SELECT p.ProductID, p.ProductName
            FROM PromotionTargets t
            JOIN Products p ON t.ProductID = p.ProductID
            WHERE t.PromotionID = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $promotionID]);

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // lấy category
        $sql = "SELECT c.CategoryID, c.CategoryName
            FROM PromotionTargets t
            JOIN Categories c ON t.CategoryID = c.CategoryID
            WHERE t.PromotionID = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $promotionID]);

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "products" => $products,
            "categories" => $categories
        ];
    }

    public function add($data)
    {
        $sql = "INSERT INTO Promotions 
            (PromotionName, StartDate, EndDate, IsActive, DiscountType, DiscountValue)
            VALUES (:name, :start, :end, :active, :type, :value)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "name" => $data['PromotionName'],
            "start" => $data['StartDate'],
            "end" => $data['EndDate'],
            "active" => $data['IsActive'],
            "type" => $data['DiscountType'],
            "value" => $data['DiscountValue']
        ]);

        return $this->conn->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE Promotions SET
                PromotionName = :name,
                StartDate = :start,
                EndDate = :end,
                IsActive = :active,
                DiscountType = :type,
                DiscountValue = :value
                WHERE PromotionID = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name" => $data['PromotionName'],
            "start" => $data['StartDate'],
            "end" => $data['EndDate'],
            "active" => $data['IsActive'],
            "type" => $data['DiscountType'],
            "value" => $data['DiscountValue'],
            "id" => $data['PromotionID']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Promotions WHERE PromotionID = :id");
        return $stmt->execute(["id" => $id]);
    }

    public function isUsed($id)
    {
        return false; // nếu chưa có liên kết
    }

    public function getBestPromotionForProduct($productID)
    {
        $sql = "SELECT p.*
        FROM Promotions p
        JOIN PromotionTargets t ON p.PromotionID = t.PromotionID
        JOIN Products pr ON pr.ProductID = :pid
        WHERE 
            (t.ProductID = :pid OR t.CategoryID = pr.CategoryID)
            AND p.IsActive = 1
            AND NOW() BETWEEN p.StartDate AND p.EndDate
        ORDER BY p.DiscountValue DESC
        LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["pid" => $productID]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function disableExpired()
    {
        $sql = "UPDATE Promotions
            SET IsActive = 0
            WHERE EndDate IS NOT NULL
            AND EndDate < NOW()
            AND IsActive = 1";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute();
    }

    public function addProductTarget($promotionID, $productID)
    {
        $sql = "INSERT INTO PromotionTargets (PromotionID, ProductID, CategoryID)
            VALUES (?, ?, NULL)";

        $this->conn->prepare($sql)->execute([$promotionID, $productID]);
    }

    public function clearTargets($promotionID)
    {
        $this->conn->prepare("DELETE FROM PromotionTargets WHERE PromotionID = ?")
            ->execute([$promotionID]);
    }

    public function addCategoryTarget($promotionID, $categoryID)
    {
        $sql = "INSERT INTO PromotionTargets (PromotionID, ProductID, CategoryID)
            VALUES (?, NULL, ?)";

        $this->conn->prepare($sql)->execute([$promotionID, $categoryID]);
    }
}
