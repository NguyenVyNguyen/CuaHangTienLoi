<?php

$page   = $_GET['controller'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id     = $_GET['id'] ?? null;
$subId  = $_GET['subId'] ?? null;

// =========================
// MAP CONTROLLER
// =========================
$controllers = [
    'dashboard' => 'DashboardController',
    'category'  => 'CategoryController',
    'supplier'  => 'SupplierController',
    'shipper'   => 'ShipperController',
    'customer'  => 'CustomerController',
    'employee'  => 'EmployeeController',
    'product'   => 'ProductController',
    'promotion' => 'PromotionController',
    'order'     => 'OrderController'
];

// =========================
// CHECK CONTROLLER
// =========================
if (!array_key_exists($page, $controllers)) {
    header("Location: index.php?controller=dashboard");
    exit;
}

// LOAD CONTROLLER
$controllerName = $controllers[$page];
require_once __DIR__ . "/controllers/$controllerName.php";

$controller = new $controllerName();

// =========================
// ROUTE ACTION
// =========================
switch ($action) {

    case 'create':
        $controller->create();
        break;

    case 'edit':
        $controller->edit($id);
        break;

    case 'save':
        $controller->save();
        break;

    case 'delete':
        $controller->delete($id);
        break;

    case 'deletePost':
        if (method_exists($controller, 'deletePost')) {
            $controller->deletePost($id);
        } else {
            $controller->delete($id);
        }
        break;

    case 'search':
        $controller->search();
        break;

    case 'searchProduct':
        if (method_exists($controller, 'searchProduct')) {
            $controller->searchProduct();
        }
        break;

    case 'detail':
        if (method_exists($controller, 'detail')) {
            $controller->detail($id);
        } else {
            $controller->index();
        }
        break;

    case 'searchAjax':
        if (method_exists($controller, 'searchAjax')) {
            $controller->searchAjax();
        }
        break;

    case 'getByCategory':
        if (method_exists($controller, 'getByCategory')) {
            $controller->getByCategory($id);
        }
        break;

    // ================= CART (🔥 THÊM MỚI) =================
    case 'addCartItem':
        if (method_exists($controller, 'addCartItem')) {
            $controller->addCartItem();
        }
        break;

    case 'showCart':
        if (method_exists($controller, 'showCart')) {
            $controller->showCart();
        }
        break;

    case 'updateCartItem':
        if (method_exists($controller, 'updateCartItem')) {
            $controller->updateCartItem();
        }
        break;

    case 'deleteCartItem':
        if (method_exists($controller, 'deleteCartItem')) {
            $controller->deleteCartItem();
        }
        break;

    case 'clearCart':
        if (method_exists($controller, 'clearCart')) {
            $controller->clearCart();
        }
        break;

    case 'createOrder':
        if (method_exists($controller, 'createOrder')) {
            $controller->createOrder();
        }
        break;

    // ================= PRODUCT EXTENSIONS (PHOTO) =================
    case 'createPhoto':
        $controller->createPhoto($id);
        break;

    case 'editPhoto':
        $controller->editPhoto($id, $subId);
        break;

    case 'savePhoto':
        $controller->savePhoto();
        break;

    case 'deletePhoto':
        $controller->deletePhoto($id, $subId);
        break;

    // ================= PRODUCT EXTENSIONS (ATTRIBUTE) =================
    case 'createAttribute':
        $controller->createAttribute($id);
        break;

    case 'editAttribute':
        $controller->editAttribute($id, $subId);
        break;

    case 'saveAttribute':
        $controller->saveAttribute();
        break;

    case 'deleteAttribute':
        $controller->deleteAttribute($id, $subId);
        break;

    // ================= CUSTOMER + EMPLOYEE =================
    case 'changePassword':
        if (method_exists($controller, 'changePassword')) {
            $controller->changePassword($id);
        } else {
            $controller->index();
        }
        break;

    case 'changePasswordPost':
        if (method_exists($controller, 'changePasswordPost')) {
            $controller->changePasswordPost();
        } else {
            $controller->index();
        }
        break;

    case 'changeRole':
        if (method_exists($controller, 'changeRole')) {
            $controller->changeRole($id);
        } else {
            $controller->index();
        }
        break;

    case 'saveRole':
        if (method_exists($controller, 'saveRole')) {
            $controller->saveRole();
        } else {
            $controller->index();
        }
        break;

    default:
        $controller->index();
        break;
}