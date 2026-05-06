<?php

class SelectListHelper
{
    private $catalogService;
    private $partnerService;
    private $dictionaryService;

    public function __construct($catalogService, $partnerService, $dictionaryService)
    {
        $this->catalogService = $catalogService;
        $this->partnerService = $partnerService;
        $this->dictionaryService = $dictionaryService;
    }

    // =========================
    // PROVINCES
    // =========================
    public function provinces(): array
    {
        $list = [
            ["value" => "", "text" => "-- Tỉnh/Thành phố --"]
        ];

        $data = $this->dictionaryService->listProvinces();

        if ($data) {
            foreach ($data as $item) {
                $val = is_array($item) ? $item["ProvinceName"] : $item->ProvinceName;
                $list[] = [
                    "value" => $val,
                    "text" => $val
                ];
            }
        }

        return $list;
    }

    // =========================
    // CATEGORIES
    // =========================
    public function categories(): array
    {

        $input = [
            "page" => 1,
            "pageSize" => 9999,
            "searchValue" => ""
        ];

        $result = $this->catalogService->listCategories($input);

        if (isset($result["DataItems"])) {
            foreach ($result["DataItems"] as $item) {
                $list[] = [
                    "value" => is_array($item) ? $item["CategoryID"] : $item->CategoryID,
                    "text" => is_array($item) ? $item["CategoryName"] : $item->CategoryName
                ];
            }
        }

        return $list;
    }

    // =========================
    // SUPPLIERS
    // =========================
    public function suppliers(): array
    {

        $input = [
            "page" => 1,
            "pageSize" => 9999,
            "searchValue" => ""
        ];
        $result = $this->partnerService->listSuppliers($input);

        if (isset($result["DataItems"])) {
            foreach ($result["DataItems"] as $item) {
                $list[] = [
                    "value" => is_array($item) ? $item["SupplierID"] : $item->SupplierID,
                    "text" => is_array($item) ? $item["SupplierName"] : $item->SupplierName
                ];
            }
        }

        return $list;
    }

    // =========================
    // CUSTOMERS
    // =========================
    public static function searchCustomers($keyword)
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
        SELECT CustomerID, CustomerName
        FROM Customers
        WHERE CustomerName LIKE :kw
        LIMIT 10
    ");

        $stmt->execute([
            "kw" => "%$keyword%"
        ]);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = [
                "value" => $row["CustomerID"],
                "text" => $row["CustomerName"]
            ];
        }

        return $data;
    }

    // =========================
    // ORDER STATUS 
    // =========================
    public function orderStatus(): array
    {
        return [
            ["value" => "-99", "text" => "-- Trạng thái ---"],
            ["value" => "0", "text" => "Mới (Chờ duyệt)"],
            ["value" => "1", "text" => "Đã duyệt (Chờ giao hàng)"],
            ["value" => "4", "text" => "Đang giao hàng"],
            ["value" => "5", "text" => "Hoàn thành"],
            ["value" => "2", "text" => "Từ chối"],
            ["value" => "3", "text" => "Hủy"]
        ];
    }
}
