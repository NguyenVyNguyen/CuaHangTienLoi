<?php

require_once __DIR__ . "/../../app/services/SalesService.php";
require_once __DIR__ . "/../../app/services/CatalogService.php";
require_once __DIR__ . "/../../app/core/AppContext.php";
require_once __DIR__ . "/../../app/services/SelectListHelper.php";
require_once __DIR__ . "/../../app/services/DictionaryService.php";
require_once __DIR__ . "/../../app/core/ApiResult.php";
require_once __DIR__ . "/../../app/services/PartnerService.php";

class OrderController
{
    private const ORDER_SEARCH = "OrderSearchInput";
    private const PRODUCT_SEARCH = "ProductSearch";

    private $salesService;
    private $catalogService;
    private $dictionaryService;
    private $partnerService;

    public function __construct()
    {
        $this->salesService = new SalesService();
        $this->catalogService = new CatalogService();
        $this->dictionaryService = new DictionaryService();
        $this->partnerService = new PartnerService();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =====================
    // RENDER
    // =====================
    private function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/' . $view . '.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    // =====================
    // CREATE VIEW
    // =====================
    public function create()
    {
        $model = AppContext::getSession(self::PRODUCT_SEARCH);

        if (!$model) {
            $model = [
                "page" => 1,
                "pageSize" => 5,
                "SearchValue" => "",
                "CategoryID" => 0,
                "SupplierID" => 0
            ];
        }

        $helper = new SelectListHelper(
            $this->catalogService,
            $this->salesService,
            $this->dictionaryService
        );

        $provinces = $helper->provinces();

        // Fix undefined method 'listCustomers'
        $input = [];
        $customers = $this->partnerService->listCustomers($input);

        $cart = $_SESSION["cart"] ?? [];

        $this->render("order/create", compact(
            "model",
            "provinces",
            "customers",
            "cart"
        ));
    }

    // =====================
    // SEARCH PRODUCT (AJAX)
    // =====================
    public function searchProduct()
    {
        $page = (int)($_GET["page"] ?? 1);
        $pageSize = (int)($_GET["pageSize"] ?? 3);

        $input = [
            "page" => $_GET["page"] ?? 1,
            "pageSize" => $_GET["pageSize"] ?? 3,
            "SearchValue" => $_GET["SearchValue"] ?? "",
            "CategoryID" => $_GET["CategoryID"] ?? 0,
            "SupplierID" => $_GET["SupplierID"] ?? 0
        ];

        $result = $this->catalogService->listProducts($input);

        $rowCount = $result["RowCount"] ?? 0;

        $data = [
            "DataItems" => $result["DataItems"] ?? [],
            "Page" => $page,
            "PageCount" => ceil($rowCount / $pageSize)
        ];

        AppContext::setSession(self::PRODUCT_SEARCH, $input);

        $dataVariable = ["data" => $data];
        extract($dataVariable);

        include __DIR__ . "/../views/order/searchProduct.php";
    }

    // =====================
    // CART
    // =====================
    private function getCart()
    {
        return $_SESSION["cart"] ?? [];
    }

    private function saveCart($cart)
    {
        $_SESSION["cart"] = $cart;
    }

    public function showCart()
    {
        $cart = $_SESSION["cart"] ?? [];

        if (!is_array($cart)) {
            $cart = [];
        }

        foreach ($cart as &$item) {
            $item["TotalPrice"] = $item["Quantity"] * $item["SalePrice"];
        }

        include __DIR__ . "/../views/order/showcart.php";
    }

    // =====================
    // ADD ITEM
    // =====================
    public function addCartItem()
    {
        ob_clean();

        $productID = (int)($_POST["productId"] ?? 0);
        $qty = (int)($_POST["quantity"] ?? 0);

        if ($qty <= 0) {
            (new ApiResult(0, "Số lượng không hợp lệ"))->toJson();
            return;
        }

        $product = $this->catalogService->getProduct($productID);

        if (!$product) {
            (new ApiResult(0, "Không tìm thấy sản phẩm"))->toJson();
            return;
        }

        $product = (array)$product; // 🔥 FIX: ép về array để đồng bộ

        $price = (float)$product["Price"];

        // ===== LẤY CART =====
        $cart = $_SESSION["cart"] ?? [];

        if (!is_array($cart)) {
            $cart = [];
        }

        // ===== ADD / UPDATE =====
        if (isset($cart[$productID])) {
            $cart[$productID]["Quantity"] += $qty;
        } else {
            $cart[$productID] = [
                "ProductID" => $productID,
                "ProductName" => $product["ProductName"],
                "Unit" => $product["Unit"],
                "Quantity" => $qty,
                "SalePrice" => $price
            ];
        }

        $_SESSION["cart"] = $cart;

        ob_start();

        $cart = $_SESSION["cart"] ?? [];

        foreach ($cart as &$item) {
            $item["TotalPrice"] = $item["Quantity"] * $item["SalePrice"];
        }

        include __DIR__ . "/../views/order/showcart.php";

        $html = ob_get_clean();

        echo json_encode([
            "code" => 1,
            "html" => $html
        ]);
    }

    // =====================
    // UPDATE ITEM
    // =====================
    public function updateCartItem()
    {
        $productID = (int)($_POST["productId"] ?? 0);
        $qty = (int)($_POST["quantity"] ?? 0);

        if ($qty <= 0) return;

        $cart = $this->getCart();

        if (isset($cart[$productID])) {
            $cart[$productID]["Quantity"] = $qty;
        }

        $this->saveCart($cart);

        (new ApiResult(1))->toJson();
    }

    // =====================
    // DELETE ITEM
    // =====================
    public function deleteCartItem()
    {
        $productID = $_POST["productId"] ?? 0;

        $cart = $this->getCart();
        unset($cart[$productID]);

        $this->saveCart($cart);

        (new ApiResult(1))->toJson();
    }

    // =====================
    // CLEAR CART
    // =====================
    public function clearCart()
    {
        unset($_SESSION["cart"]);
        (new ApiResult(1))->toJson();
    }

    // =====================
    // CREATE ORDER
    // =====================
    public function createOrder()
    {
        $customerID = (int)($_POST["customerID"] ?? 0);
        $province = trim($_POST["province"] ?? "");
        $address = trim($_POST["address"] ?? "");

        $cart = $this->getCart();

        if (empty($cart)) {
            (new ApiResult(0, "Giỏ hàng trống"))->toJson();
            return;
        }

        if ($customerID <= 0) {
            (new ApiResult(0, "Chưa chọn khách hàng"))->toJson();
            return;
        }

        if ($province == "" || $address == "") {
            (new ApiResult(0, "Thiếu địa chỉ"))->toJson();
            return;
        }

        $orderID = $this->salesService->addOrder($customerID, $province, $address);

        foreach ($cart as $item) {
            $this->salesService->addDetail((object)[
                "OrderID" => $orderID,
                "ProductID" => $item["ProductID"],
                "Quantity" => $item["Quantity"],
                "SalePrice" => $item["SalePrice"]
            ]);
        }

        $this->clearCart();

        (new ApiResult($orderID))->toJson();
    }
}
