<?php

require_once __DIR__ . "/../Interfaces/IOrderRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class OrderRepository implements IOrderRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    // ======================================
    // ORDER LIST
    // ======================================
    public function list($input)
    {
        $page = $input['page'] ?? 1;
        $pageSize = $input['pageSize'] ?? 10;

        $search = trim($input['SearchValue'] ?? '');
        $status = (int)($input['Status'] ?? 0);
        $dateFrom = $input['DateFrom'] ?? null;
        $dateTo = $input['DateTo'] ?? null;

        $offset = ($page - 1) * $pageSize;

        $result = [
            "RowCount" => 0,
            "DataItems" => [],
            "Pages" => []
        ];

        // ================= COUNT =================
        $sql = "
        SELECT COUNT(*)
        FROM Orders o
        LEFT JOIN Customers c ON o.CustomerID = c.CustomerID
        WHERE c.CustomerName LIKE :search
        AND (:status = 0 OR o.Status = :status)
        AND (:dateFrom IS NULL OR o.OrderTime >= :dateFrom)
        AND (:dateTo IS NULL OR o.OrderTime <= :dateTo)
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => "%$search%",
            "status" => $status,
            "dateFrom" => $dateFrom,
            "dateTo" => $dateTo
        ]);

        $result["RowCount"] = $stmt->fetchColumn();

        if ($result["RowCount"] == 0) return $result;

        // ================= DATA =================
        $sql = "
        SELECT o.*,
               c.CustomerName,
               e.FullName AS EmployeeName,
               IFNULL((
                    SELECT SUM(od.Quantity * od.SalePrice)
                    FROM OrderDetails od
                    WHERE od.OrderID = o.OrderID
               ),0) AS SumOfPrice
        FROM Orders o
        LEFT JOIN Customers c ON o.CustomerID = c.CustomerID
        LEFT JOIN Employees e ON o.EmployeeID = e.EmployeeID
        WHERE c.CustomerName LIKE :search
        AND (:status = 0 OR o.Status = :status)
        AND (:dateFrom IS NULL OR o.OrderTime >= :dateFrom)
        AND (:dateTo IS NULL OR o.OrderTime <= :dateTo)
        ORDER BY o.OrderTime DESC
        LIMIT $offset, $pageSize
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => "%$search%",
            "status" => $status,
            "dateFrom" => $dateFrom,
            "dateTo" => $dateTo
        ]);

        $result["DataItems"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ================= PAGING =================
        $totalPages = ceil($result["RowCount"] / $pageSize);

        $start = max(1, $page - 2);
        $end   = min($totalPages, $page + 2);

        if ($start > 1) {
            $result["Pages"][] = ["Page" => 1, "IsCurrent" => false];
            if ($start > 2) $result["Pages"][] = ["Page" => 0];
        }

        for ($i = $start; $i <= $end; $i++) {
            $result["Pages"][] = [
                "Page" => $i,
                "IsCurrent" => ($i == $page)
            ];
        }

        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $result["Pages"][] = ["Page" => 0];
            }
            $result["Pages"][] = ["Page" => $totalPages, "IsCurrent" => false];
        }

        return $result;
    }

    // ======================================
    // GET ORDER
    // ======================================
    public function get($orderID)
    {
        $sql = "
            SELECT o.*,
                   c.CustomerName,
                   c.ContactName AS CustomerContactName,
                   c.Email AS CustomerEmail,
                   c.Phone AS CustomerPhone,
                   c.Address AS CustomerAddress,
                   e.FullName AS EmployeeName,
                   s.ShipperName,
                   s.Phone AS ShipperPhone
            FROM Orders o
            LEFT JOIN Customers c ON o.CustomerID = c.CustomerID
            LEFT JOIN Employees e ON o.EmployeeID = e.EmployeeID
            LEFT JOIN Shippers s ON o.ShipperID = s.ShipperID
            WHERE o.OrderID = :id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $orderID]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // ======================================
    // ADD ORDER
    // ======================================
    public function add($data)
    {
        $sql = "
            INSERT INTO Orders
            (CustomerID, OrderTime, DeliveryProvince, DeliveryAddress,
             EmployeeID, AcceptTime, ShipperID, ShippedTime, FinishedTime, Status)
            VALUES
            (:customer, :time, :province, :address,
             :employee, :accept, :shipper, :shipped, :finished, :status)
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "customer" => $data->CustomerID,
            "time" => $data->OrderTime,
            "province" => $data->DeliveryProvince,
            "address" => $data->DeliveryAddress,
            "employee" => $data->EmployeeID,
            "accept" => $data->AcceptTime,
            "shipper" => $data->ShipperID,
            "shipped" => $data->ShippedTime,
            "finished" => $data->FinishedTime,
            "status" => $data->Status
        ]);

        return $this->conn->lastInsertId();
    }

    // ======================================
    // UPDATE ORDER
    // ======================================
    public function update($data)
    {
        $sql = "
            UPDATE Orders SET
                CustomerID = :customer,
                OrderTime = :time,
                DeliveryProvince = :province,
                DeliveryAddress = :address,
                EmployeeID = :employee,
                AcceptTime = :accept,
                ShipperID = :shipper,
                ShippedTime = :shipped,
                FinishedTime = :finished,
                Status = :status
            WHERE OrderID = :id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "customer" => $data->CustomerID,
            "time" => $data->OrderTime,
            "province" => $data->DeliveryProvince,
            "address" => $data->DeliveryAddress,
            "employee" => $data->EmployeeID,
            "accept" => $data->AcceptTime,
            "shipper" => $data->ShipperID,
            "shipped" => $data->ShippedTime,
            "finished" => $data->FinishedTime,
            "status" => $data->Status,
            "id" => $data->OrderID
        ]);
    }

    // ======================================
    // DELETE ORDER
    // ======================================
    public function delete($orderID)
    {
        // delete details first
        $this->conn->prepare("DELETE FROM OrderDetails WHERE OrderID = ?")
            ->execute([$orderID]);

        $stmt = $this->conn->prepare("DELETE FROM Orders WHERE OrderID = ?");
        return $stmt->execute([$orderID]);
    }

    // ======================================
    // ORDER DETAILS
    // ======================================

    public function listDetails($orderID)
    {
        $sql = "
            SELECT od.*,
                   p.ProductName,
                   p.Unit,
                   p.Photo
            FROM OrderDetails od
            LEFT JOIN Products p ON od.ProductID = p.ProductID
            WHERE od.OrderID = :id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $orderID]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDetail($orderID, $productID)
    {
        $sql = "
            SELECT od.*,
                   p.ProductName,
                   p.Unit,
                   p.Photo
            FROM OrderDetails od
            LEFT JOIN Products p ON od.ProductID = p.ProductID
            WHERE od.OrderID = :orderID
            AND od.ProductID = :productID
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "orderID" => $orderID,
            "productID" => $productID
        ]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // UPSERT DETAIL (giống IF EXISTS SQL Server)
    public function addDetail($data)
    {
        $check = $this->conn->prepare("
            SELECT Quantity FROM OrderDetails
            WHERE OrderID = ? AND ProductID = ?
        ");

        $check->execute([$data->OrderID, $data->ProductID]);
        $existing = $check->fetchColumn();

        if ($existing) {
            $sql = "
                UPDATE OrderDetails
                SET Quantity = Quantity + :qty,
                    SalePrice = :price
                WHERE OrderID = :orderID AND ProductID = :productID
            ";
        } else {
            $sql = "
                INSERT INTO OrderDetails
                (OrderID, ProductID, Quantity, SalePrice)
                VALUES (:orderID, :productID, :qty, :price)
            ";
        }

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "orderID" => $data->OrderID,
            "productID" => $data->ProductID,
            "qty" => $data->Quantity,
            "price" => $data->SalePrice
        ]);
    }

    public function updateDetail($data)
    {
        $sql = "
            UPDATE OrderDetails
            SET Quantity = :qty,
                SalePrice = :price
            WHERE OrderID = :orderID
            AND ProductID = :productID
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "qty" => $data->Quantity,
            "price" => $data->SalePrice,
            "orderID" => $data->OrderID,
            "productID" => $data->ProductID
        ]);
    }

    public function deleteDetail($orderID, $productID)
    {
        $stmt = $this->conn->prepare("
            DELETE FROM OrderDetails
            WHERE OrderID = ? AND ProductID = ?
        ");

        return $stmt->execute([$orderID, $productID]);
    }
}
