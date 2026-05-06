<?php

require_once __DIR__ . "/../../database/Database.php";

class DashboardRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getDashboardData()
    {
        $dashboard = new stdClass();

        // =====================
        // TOTAL COUNTS
        // =====================
        $dashboard->TotalCustomers = $this->conn
            ->query("SELECT COUNT(*) FROM Customers")
            ->fetchColumn();

        $dashboard->TotalProducts = $this->conn
            ->query("SELECT COUNT(*) FROM Products")
            ->fetchColumn();

        $dashboard->TotalOrders = $this->conn
            ->query("SELECT COUNT(*) FROM Orders")
            ->fetchColumn();

        // =====================
        // REVENUE TODAY
        // =====================
        $sqlRevenueToday = "
            SELECT IFNULL(SUM(od.Quantity * od.SalePrice), 0)
            FROM OrderDetails od
            JOIN Orders o ON od.OrderID = o.OrderID
            WHERE DATE(o.OrderTime) = CURDATE()
            AND o.Status = 4
        ";

        $dashboard->TotalRevenueToday = $this->conn
            ->query($sqlRevenueToday)
            ->fetchColumn();

        // =====================
        // MONTHLY REVENUE
        // =====================
        $sqlMonthly = "
            SELECT MONTH(o.OrderTime) AS Month,
                   IFNULL(SUM(od.Quantity * od.SalePrice), 0) AS Revenue
            FROM OrderDetails od
            JOIN Orders o ON od.OrderID = o.OrderID
            WHERE YEAR(o.OrderTime) = YEAR(CURDATE())
            AND o.Status = 4
            GROUP BY MONTH(o.OrderTime)
        ";

        $stmt = $this->conn->query($sqlMonthly);
        $monthlyList = $stmt->fetchAll(PDO::FETCH_OBJ);

        $dashboard->MonthlyRevenues = [];

        for ($i = 1; $i <= 12; $i++) {
            $found = null;

            foreach ($monthlyList as $m) {
                if ((int)$m->Month === $i) {
                    $found = $m;
                    break;
                }
            }

            $dashboard->MonthlyRevenues[] = $found ?? (object)[
                "Month" => $i,
                "Revenue" => 0
            ];
        }

        // =====================
        // TOP PRODUCTS
        // =====================
        $sqlTop = "
            SELECT p.ProductName,
                   IFNULL(SUM(od.Quantity), 0) AS TotalQty
            FROM OrderDetails od
            JOIN Products p ON od.ProductID = p.ProductID
            JOIN Orders o ON od.OrderID = o.OrderID
            WHERE o.Status = 4
            GROUP BY p.ProductID, p.ProductName
            ORDER BY SUM(od.Quantity) DESC
            LIMIT 5
        ";

        $dashboard->TopProducts = $this->conn
            ->query($sqlTop)
            ->fetchAll(PDO::FETCH_OBJ);

        // =====================
        // PENDING ORDERS
        // =====================
        $sqlPending = "
            SELECT o.OrderID,
                   o.OrderTime,
                   c.CustomerName,
                   o.Status,
                   IFNULL((
                        SELECT SUM(Quantity * SalePrice)
                        FROM OrderDetails
                        WHERE OrderID = o.OrderID
                   ), 0) AS TotalMoney
            FROM Orders o
            LEFT JOIN Customers c ON o.CustomerID = c.CustomerID
            WHERE o.Status IN (1,2,3)
            ORDER BY o.OrderTime DESC
            LIMIT 10
        ";

        $dashboard->PendingOrders = $this->conn
            ->query($sqlPending)
            ->fetchAll(PDO::FETCH_OBJ);

        return $dashboard;
    }
}