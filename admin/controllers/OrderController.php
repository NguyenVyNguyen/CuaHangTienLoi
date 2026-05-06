<?php
session_start();

require_once __DIR__ . "/../../app/services/SalesService.php";
require_once __DIR__ . "/../../app/services/CatalogService.php";
require_once __DIR__ . "/../../app/core/AppContext.php";
require_once __DIR__ . "/../../app/services/SelectListHelper.php";
require_once __DIR__ . "/../../app/services/DictionaryService.php";
require_once __DIR__ . "/../../app/core/ApiResult.php";

class OrderController
{
    private const ORDER_SEARCH = "OrderSearchInput";
    private const PRODUCT_SEARCH = "ProductSearch";

    private $salesService;
    private $catalogService;
    private $dictionaryService;

    public function __construct()
    {
        $this->salesService = new SalesService();
        $this->catalogService = new CatalogService();
        $this->dictionaryService = new DictionaryService();

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
    // LIST ORDER
    // =====================
    public function index()
    {
        $model = AppContext::getSession(self::ORDER_SEARCH);

        if (!$model) {
            $model = [
                "page" => 1,
                "pageSize" => 10,
                "SearchValue" => "",
                "Status" => 0,
                "DateFrom" => "",
                "DateTo" => ""
            ];
        }

        $this->render("order/index", compact("model"));
    }

    public function search()
    {
        $input = [
            "page" => $_GET["page"] ?? 1,
            "pageSize" => 10,
            "SearchValue" => $_GET["SearchValue"] ?? "",
            "Status" => $_GET["Status"] ?? 0,
            "DateFrom" => $_GET["DateFrom"] ?? null,
            "DateTo" => $_GET["DateTo"] ?? null
        ];

        $result = $this->salesService->listOrders($input);
        AppContext::setSession(self::ORDER_SEARCH, $input);

        include __DIR__ . "/../views/order/search.php";
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

        $helper = new SelectListHelper($this->catalogService, $this->salesService, $this->dictionaryService);
        $provinces = $helper->provinces();

        $this->render("order/create", compact("model", "provinces"));
    }

    // =====================
    // SEARCH PRODUCT
    // =====================
    public function searchProduct()
    {
        $input = [
            "page" => $_GET["page"] ?? 1,
            "pageSize" => $_GET["pageSize"] ?? 5,
            "SearchValue" => $_GET["SearchValue"] ?? "",
            "CategoryID" => $_GET["CategoryID"] ?? 0,
            "SupplierID" => $_GET["SupplierID"] ?? 0
        ];

        $data = $this->catalogService->listProducts($input);
        AppContext::setSession(self::PRODUCT_SEARCH, $input);

        include __DIR__ . "/../views/order/searchProduct.php";
        // Truyền dữ liệu vào view
        extract(["data" => $data]);
    }

    // =====================
    // SEARCH CUSTOMER (AJAX)
    // =====================
    public function searchCustomer()
    {
        $keyword = trim($_GET['keyword'] ?? '');

        if (strlen($keyword) < 2) {
            echo json_encode([]);
            return;
        }

        $helper = new SelectListHelper($this->catalogService, $this->salesService, $this->dictionaryService);
        $customers = $helper->searchCustomers($keyword);

        $data = [];
        foreach ($customers as $c) {
            $data[] = [
                "id" => $c["value"],
                "text" => $c["text"]
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // =====================
    // CART SESSION
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
        $cart = $this->getCart();

        foreach ($cart as &$item) {
            $item["TotalPrice"] = $item["Quantity"] * $item["SalePrice"];
        }

        include __DIR__ . "/../views/order/showcart.php";
        // Truyền dữ liệu vào view
        extract(["cart" => $cart]);
    }

    // =====================
    // ADD CART ITEM
    // =====================
    public function addCartItem()
    {
        $productID = (int)($_POST["productId"] ?? 0);
        $qty = (int)($_POST["quantity"] ?? 0);
        $price = (float)($_POST["price"] ?? 0);

        if ($qty <= 0) {
            (new ApiResult(0, "Số lượng không hợp lệ"))->toJson();
            return;
        }

        if ($price < 0) {
            (new ApiResult(0, "Giá không hợp lệ"))->toJson();
            return;
        }

        $product = $this->catalogService->getProduct($productID);

        if (!$product) {
            (new ApiResult(0, "Mặt hàng không tồn tại"))->toJson();
            return;
        }

        if (!$product["IsSelling"]) {
            (new ApiResult(0, "Mặt hàng không được bán"))->toJson();
            return;
        }

        $cart = $this->getCart();

        if (isset($cart[$productID])) {
            $cart[$productID]["Quantity"] += $qty;
        } else {
            $cart[$productID] = [
                "ProductID" => $productID,
                "ProductName" => $product["ProductName"],
                "Unit" => $product["Unit"],
                "Quantity" => $qty,
                "SalePrice" => $price,
                "Photo" => $product["Photo"] ?? "nophoto.png"
            ];
        }

        $this->saveCart($cart);

        (new ApiResult(1))->toJson();
    }

    // =====================
    // UPDATE CART ITEM
    // =====================
    public function updateCartItem()
    {
        $productID = (int)$_POST["productId"];
        $qty = (int)$_POST["quantity"];
        $price = (float)$_POST["salePrice"];

        if ($productID <= 0 || $qty <= 0 || $price < 0) {
            (new ApiResult(0, "Dữ liệu không hợp lệ"))->toJson();
            return;
        }

        $cart = $this->getCart();

        if (isset($cart[$productID])) {
            $cart[$productID]["Quantity"] = $qty;
            $cart[$productID]["SalePrice"] = $price;
        }

        $this->saveCart($cart);

        (new ApiResult(1))->toJson();
    }

    // =====================
    // DELETE CART ITEM
    // =====================
    public function deleteCartItem()
    {
        $productID = $_POST["productId"];

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

        if (!$cart || count($cart) == 0) {
            (new ApiResult(0, "Giỏ hàng đang trống"))->toJson();
            return;
        }

        if ($customerID <= 0) {
            (new ApiResult(0, "Vui lòng chọn khách hàng"))->toJson();
            return;
        }

        if ($province == "" || $address == "") {
            (new ApiResult(0, "Vui lòng nhập địa chỉ"))->toJson();
            return;
        }

        try {
            $orderID = $this->salesService->addOrder($customerID, $province, $address);

            if ($orderID <= 0) {
                (new ApiResult(0, "Không thể tạo đơn hàng"))->toJson();
                return;
            }

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
        } catch (Exception $ex) {
            (new ApiResult(0, "Lỗi: " . $ex->getMessage()))->toJson();
        }
    }

    // =====================
    // DETAIL
    // =====================
    public function detail($id)
    {
        $order = $this->salesService->getOrder($id);
        $details = $this->salesService->listDetails($id);

        $this->render("order/detail", compact("order", "details"));
    }

    // =====================
    // WORKFLOW
    // =====================
    public function accept($id)
    {
        $this->salesService->acceptOrder($id, 1);
        header("Location: index.php?controller=order&action=detail&id=$id");
    }

    public function reject($id)
    {
        $this->salesService->rejectOrder($id, 1);
        header("Location: index.php?controller=order&action=detail&id=$id");
    }

    public function cancel($id)
    {
        $this->salesService->cancelOrder($id);
        header("Location: index.php?controller=order&action=detail&id=$id");
    }

    public function ship($id)
    {
        $shipperID = $_POST["shipperID"];
        $this->salesService->shipOrder($id, $shipperID);

        header("Location: index.php?controller=order&action=detail&id=$id");
    }

    public function finish($id)
    {
        $this->salesService->completeOrder($id);
        header("Location: index.php?controller=order&action=detail&id=$id");
    }
}
