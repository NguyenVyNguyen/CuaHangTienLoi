<?php

require_once __DIR__ . "/../Interfaces/IProductRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class ProductRepository implements IProductRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    // =========================
    // PRODUCT LIST
    // =========================
    public function list($input)
    {
        $page = $input['page'] ?? 1;
        $pageSize = $input['pageSize'] ?? 10;

        $search = trim($input['SearchValue'] ?? '');
        $categoryID = (int)($input['CategoryID'] ?? 0);
        $supplierID = (int)($input['SupplierID'] ?? 0);

        $offset = ($page - 1) * $pageSize;

        $result = [
            "RowCount" => 0,
            "DataItems" => [],
            "Pages" => []
        ];

        // ================= COUNT =================
        $sql = "SELECT COUNT(*)
            FROM Products p
            WHERE p.ProductName LIKE :searchLike
            AND (:category = 0 OR p.CategoryID = :category)
            AND (:supplier = 0 OR p.SupplierID = :supplier)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "searchLike" => "%$search%",
            "category" => $categoryID,
            "supplier" => $supplierID
        ]);

        $result["RowCount"] = $stmt->fetchColumn();

        if ($result["RowCount"] == 0) return $result;

        // ================= DATA =================
        $sql = "SELECT 
            p.*, 
            c.CategoryName, 
            s.SupplierName,

            MAX(pr.PromotionName) AS PromotionName,
            MAX(pr.DiscountType) AS DiscountType,
            COALESCE(MAX(pr.DiscountValue), 0) AS DiscountValue,

            CASE 
                WHEN MAX(pr.DiscountType) = 'percent' 
                    THEN p.Price - (p.Price * MAX(pr.DiscountValue) / 100)
                WHEN MAX(pr.DiscountType) = 'amount' 
                    THEN p.Price - MAX(pr.DiscountValue)
                ELSE p.Price
            END AS FinalPrice

        FROM Products p

        LEFT JOIN Categories c ON p.CategoryID = c.CategoryID
        LEFT JOIN Suppliers s ON p.SupplierID = s.SupplierID

        LEFT JOIN (
            SELECT 
                t.ProductID,
                t.CategoryID,
                p.PromotionName,
                p.DiscountType,
                p.DiscountValue,
                p.StartDate,
                p.EndDate
            FROM Promotions p
            JOIN PromotionTargets t 
                ON p.PromotionID = t.PromotionID
            WHERE p.IsActive = 1
        ) pr 
        ON (
            (pr.ProductID IS NOT NULL AND pr.ProductID = p.ProductID)
            OR 
            (pr.CategoryID IS NOT NULL AND pr.CategoryID = p.CategoryID)
        )
        AND NOW() BETWEEN pr.StartDate AND pr.EndDate

        WHERE p.ProductName LIKE :searchLike
        AND (:category = 0 OR p.CategoryID = :category)
        AND (:supplier = 0 OR p.SupplierID = :supplier)

        GROUP BY p.ProductID

        ORDER BY 
            COALESCE(MAX(pr.DiscountValue),0) DESC,
            p.ProductName

        LIMIT $offset, $pageSize";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "searchLike" => "%$search%",
            "category" => $categoryID,
            "supplier" => $supplierID
        ]);

        $result["DataItems"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ================= PAGING =================
        $totalPages = ceil($result["RowCount"] / $pageSize);

        $start = max(1, $page - 2);
        $end   = min($totalPages, $page + 10); // 👉 chỉnh về +2 cho đẹp

        // FIRST + ...
        if ($start > 1) {
            $result["Pages"][] = [
                "Page" => 1,
                "IsCurrent" => false
            ];

            if ($start > 2) {
                $result["Pages"][] = ["Page" => 0]; // dấu ...
            }
        }

        // MAIN
        for ($i = $start; $i <= $end; $i++) {
            $result["Pages"][] = [
                "Page" => $i,
                "IsCurrent" => ($i == $page)
            ];
        }

        // ... + LAST
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $result["Pages"][] = ["Page" => 0];
            }

            $result["Pages"][] = [
                "Page" => $totalPages,
                "IsCurrent" => false
            ];
        }

        return $result;
    }

    // =========================
    // GET PRODUCT
    // =========================
    public function get($productID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Products WHERE ProductID = :id");
        $stmt->execute(["id" => $productID]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // =========================
    // ADD PRODUCT
    // =========================
    public function add($data)
    {
        $sql = "INSERT INTO Products
                (ProductName, ProductDescription, SupplierID, CategoryID, Unit, Price, Photo, IsSelling)
                VALUES
                (:name, :desc, :supplier, :category, :unit, :price, :photo, :selling)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "name" => $data->ProductName,
            "desc" => $data->ProductDescription,
            "supplier" => $data->SupplierID,
            "category" => $data->CategoryID,
            "unit" => $data->Unit,
            "price" => $data->Price,
            "photo" => $data->Photo,
            "selling" => $data->IsSelling
        ]);

        return $this->conn->lastInsertId();
    }

    // =========================
    // UPDATE PRODUCT
    // =========================
    public function update($data)
    {
        $sql = "UPDATE Products SET
                ProductName = :name,
                ProductDescription = :desc,
                SupplierID = :supplier,
                CategoryID = :category,
                Unit = :unit,
                Price = :price,
                Photo = :photo,
                IsSelling = :selling
                WHERE ProductID = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name" => $data->ProductName,
            "desc" => $data->ProductDescription,
            "supplier" => $data->SupplierID,
            "category" => $data->CategoryID,
            "unit" => $data->Unit,
            "price" => $data->Price,
            "photo" => $data->Photo,
            "selling" => $data->IsSelling,
            "id" => $data->ProductID
        ]);
    }

    // =========================
    // DELETE PRODUCT
    // =========================
    public function delete($productID)
    {
        $stmt = $this->conn->prepare("DELETE FROM Products WHERE ProductID = :id");
        return $stmt->execute(["id" => $productID]);
    }

    public function isUsed($productID)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM OrderDetails WHERE ProductID = :id");
        $stmt->execute(["id" => $productID]);

        return $stmt->fetchColumn() > 0;
    }

    // =========================
    // ATTRIBUTES
    // =========================
    public function listAttributes($productID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ProductAttributes WHERE ProductID = :id ORDER BY DisplayOrder");
        $stmt->execute(["id" => $productID]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAttribute($attributeID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ProductAttributes WHERE AttributeID = :id");
        $stmt->execute(["id" => $attributeID]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addAttribute($data)
    {
        $sql = "INSERT INTO ProductAttributes
                (ProductID, AttributeName, AttributeValue, DisplayOrder)
                VALUES (:pid, :name, :value, :order)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "pid" => $data->ProductID,
            "name" => $data->AttributeName,
            "value" => $data->AttributeValue,
            "order" => $data->DisplayOrder
        ]);

        return $this->conn->lastInsertId();
    }

    public function updateAttribute($data)
    {
        $sql = "UPDATE ProductAttributes SET
                AttributeName = :name,
                AttributeValue = :value,
                DisplayOrder = :order
                WHERE AttributeID = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name" => $data->AttributeName,
            "value" => $data->AttributeValue,
            "order" => $data->DisplayOrder,
            "id" => $data->AttributeID
        ]);
    }

    public function deleteAttribute($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM ProductAttributes WHERE AttributeID = :id");
        return $stmt->execute(["id" => $id]);
    }

    // =========================
    // PHOTOS
    // =========================
    public function listPhotos($productID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ProductPhotos WHERE ProductID = :id ORDER BY DisplayOrder");
        $stmt->execute(["id" => $productID]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPhoto($photoID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ProductPhotos WHERE PhotoID = :id");
        $stmt->execute(["id" => $photoID]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addPhoto($data)
    {
        $sql = "INSERT INTO ProductPhotos
                (ProductID, Photo, Description, DisplayOrder, IsHidden)
                VALUES (:pid, :photo, :desc, :order, :hidden)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "pid" => $data->ProductID,
            "photo" => $data->Photo,
            "desc" => $data->Description,
            "order" => $data->DisplayOrder,
            "hidden" => $data->IsHidden
        ]);

        return $this->conn->lastInsertId();
    }

    public function updatePhoto($data)
    {
        $sql = "UPDATE ProductPhotos SET
                Photo = :photo,
                Description = :desc,
                DisplayOrder = :order,
                IsHidden = :hidden
                WHERE PhotoID = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "photo" => $data->Photo,
            "desc" => $data->Description,
            "order" => $data->DisplayOrder,
            "hidden" => $data->IsHidden,
            "id" => $data->PhotoID
        ]);
    }

    public function deletePhoto($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM ProductPhotos WHERE PhotoID = :id");
        return $stmt->execute(["id" => $id]);
    }
}
