<?php

require_once __DIR__ . '/../../app/services/CatalogService.php';
require_once __DIR__ . '/../../app/services/PartnerService.php';
require_once __DIR__ . '/../../app/core/AppContext.php';
require_once __DIR__ . '/../../app/services/SelectListHelper.php';
require_once __DIR__ . '/../../app/services/DictionaryService.php';

class ProductController
{
    private const PRODUCT_SEARCH_CONDITION = "ProductSearchCondition";
    private $catalogService;
    private $selectListHelper;

    public function __construct()
    {
        $this->catalogService = new CatalogService();

        $this->selectListHelper = new SelectListHelper(
            new CatalogService(),
            new PartnerService(),
            new DictionaryService()
        );
    }

    // =====================
    // RENDER (Dùng chung)
    // =====================
    private function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/' . $view . '.php';
        include __DIR__ . '/../views/layout/footer.php';
    }



    // ==========================================
    // QUẢN LÝ MẶT HÀNG (MAIN PRODUCT)
    // ==========================================

    public function index()
    {
        $model = AppContext::getSession(self::PRODUCT_SEARCH_CONDITION);

        if ($model == null) {
            $model = [
                'page' => 1,
                'pageSize' => AppContext::pageSize(),
                'SearchValue' => '',
                'CategoryID' => 0,
                'SupplierID' => 0,
                'MinPrice' => 0,
                'MaxPrice' => 0
            ];
        }

        $this->render('product/index', [
            'model' => $model,
            'categories' => $this->selectListHelper->categories(),
            'suppliers' => $this->selectListHelper->suppliers()
        ]);
    }

    public function search()
    {
        $input = [
            'page' => (int)($_REQUEST['page'] ?? 1),
            'pageSize' => (int)AppContext::pageSize(),
            'SearchValue' => trim($_REQUEST['SearchValue'] ?? ''),
            'CategoryID' => (int)($_REQUEST['CategoryID'] ?? 0),
            'SupplierID' => (int)($_REQUEST['SupplierID'] ?? 0),
            'MinPrice' => (float)($_REQUEST['MinPrice'] ?? 0),
            'MaxPrice' => (float)($_REQUEST['MaxPrice'] ?? 0)
        ];

        // GỌI SERVICE
        $result = $this->catalogService->listProducts($input);

        // Lưu session
        AppContext::setSession(self::PRODUCT_SEARCH_CONDITION, $input);

        include __DIR__ . '/../views/product/search.php';
    }

    public function create()
    {
        $model = [
            'ProductID' => 0,
            'ProductName' => '',
            'CategoryID' => 0,
            'SupplierID' => 0,
            'Price' => 0,
            'Photo' => 'nophoto.png'
        ];

        $this->render('product/edit', [
            'model' => $model,
            'categories' => $this->selectListHelper->categories(),
            'suppliers' => $this->selectListHelper->suppliers()
        ]);
    }

    public function edit($id)
    {
        $model = $this->catalogService->getProduct($id);

        if (!$model) {
            header("Location: index.php?controller=product");
            exit;
        }

        $photos = $this->catalogService->listPhotos($id);
        $attributes = $this->catalogService->listAttributes($id);

        $this->render('product/edit', [
            'model' => $model,
            'productID' => $id,      // 🔥 THÊM DÒNG NÀY ĐỂ FIX LỖI
            'photos' => $photos,
            'attributes' => $attributes,
            'categories' => $this->selectListHelper->categories(),
            'suppliers' => $this->selectListHelper->suppliers()
        ]);
    }

    public function save()
    {
        $data = [
            'ProductID' => $_POST['ProductID'] ?? 0,
            'ProductName' => trim($_POST['ProductName'] ?? ''),
            'CategoryID' => $_POST['CategoryID'] ?? 0,
            'SupplierID' => $_POST['SupplierID'] ?? 0,
            'Price' => $_POST['Price'] ?? 0,
            'Photo' => $_POST['Photo'] ?? 'nophoto.png'
        ];

        // Validation cơ bản
        if (empty($data['ProductName'])) {
            $this->render('product/edit', [
                'model' => $data,
                'error' => 'Tên không được trống',
                'categories' => $this->selectListHelper->categories(),
                'suppliers' => $this->selectListHelper->suppliers()
            ]);
            return;
        }

        // Xử lý Upload ảnh chính
        if (isset($_FILES['uploadPhoto']) && $_FILES['uploadPhoto']['error'] == UPLOAD_ERR_OK) {
            $fileName = time() . "_" . basename($_FILES['uploadPhoto']['name']);
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/images/products/" . $fileName;
            if (move_uploaded_file($_FILES['uploadPhoto']['tmp_name'], $targetPath)) {
                $data['Photo'] = $fileName;
            }
        }

        if ($data['ProductID'] == 0) {
            $this->catalogService->addProduct($data);
        } else {
            $this->catalogService->updateProduct($data);
        }

        header("Location: index.php?controller=product");
        exit;
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->catalogService->deleteProduct($id);
            header("Location: index.php?controller=product");
            exit;
        }

        $model = $this->catalogService->getProduct($id);
        if (!$model) {
            header("Location: index.php?controller=product");
            exit;
        }

        $allowDelete = !$this->catalogService->isUsedProduct($id);
        $this->render('product/delete', compact('model', 'allowDelete'));
    }

    // ==========================================
    // QUẢN LÝ THUỘC TÍNH (PRODUCT ATTRIBUTES)
    // ==========================================

    public function createAttribute($id)
    {
        $model = [
            'AttributeID' => 0,
            'ProductID' => $id,
            'AttributeName' => '',
            'AttributeValue' => '',
            'DisplayOrder' => 1
        ];

        $this->render('product/editAttribute', compact('model'));
    }

    public function editAttribute($id, $attributeId)
    {
        $model = $this->catalogService->getAttribute($attributeId);

        if (!$model) {
            header("Location: index.php?controller=product&action=edit&id=$id#attributes");
            exit;
        }

        $this->render('product/editAttribute', compact('model'));
    }

    public function saveAttribute()
    {
        $data = [
            'AttributeID' => $_POST['AttributeID'] ?? 0,
            'ProductID' => $_POST['ProductID'] ?? 0,
            'AttributeName' => trim($_POST['AttributeName'] ?? ''),
            'AttributeValue' => trim($_POST['AttributeValue'] ?? ''),
            'DisplayOrder' => $_POST['DisplayOrder'] ?? 1
        ];

        // Validation thuộc tính
        if (empty($data['AttributeName']) || empty($data['AttributeValue'])) {
            $this->render('product/editAttribute', ['model' => $data, 'error' => 'Tên và giá trị không được trống']);
            return;
        }

        if ($data['AttributeID'] == 0) {
            $this->catalogService->addAttribute($data);
        } else {
            $this->catalogService->updateAttribute($data);
        }

        header("Location: index.php?controller=product&action=edit&id=" . $data['ProductID'] . "#attributes");
        exit;
    }

    public function deleteAttribute($id, $attributeId)
    {
        $this->catalogService->deleteAttribute($attributeId);
        header("Location: index.php?controller=product&action=edit&id=$id#attributes");
        exit;
    }

    // ==========================================
    // QUẢN LÝ THƯ VIỆN ẢNH (PRODUCT PHOTOS)
    // ==========================================

    public function createPhoto($id)
    {
        $model = [
            'PhotoID' => 0,
            'ProductID' => $id,
            'Photo' => 'nophoto.png',
            'Description' => '',
            'DisplayOrder' => 1,
            'IsHidden' => false
        ];

        $this->render('product/editPhoto', compact('model'));
    }

    public function editPhoto($id, $photoId)
    {
        $model = $this->catalogService->getPhoto($photoId);

        if (!$model) {
            header("Location: index.php?controller=product&action=edit&id=$id#photos");
            exit;
        }

        $this->render('product/editPhoto', compact('model'));
    }

    public function savePhoto()
    {
        $data = [
            'PhotoID' => $_POST['PhotoID'] ?? 0,
            'ProductID' => $_POST['ProductID'] ?? 0,
            'Photo' => $_POST['Photo'] ?? 'nophoto.png',
            'Description' => trim($_POST['Description'] ?? ''),
            'DisplayOrder' => $_POST['DisplayOrder'] ?? 1,
            'IsHidden' => isset($_POST['IsHidden']) ? true : false
        ];

        // Validation ảnh
        if (empty($data['Description'])) {
            $this->render('product/editPhoto', ['model' => $data, 'error' => 'Mô tả không được trống']);
            return;
        }

        // Xử lý Upload ảnh trong thư viện
        if (isset($_FILES['uploadPhoto']) && $_FILES['uploadPhoto']['error'] == UPLOAD_ERR_OK) {
            $fileName = time() . "_" . basename($_FILES['uploadPhoto']['name']);
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/images/productphotos/" . $fileName;
            if (move_uploaded_file($_FILES['uploadPhoto']['tmp_name'], $targetPath)) {
                $data['Photo'] = $fileName;
            }
        }

        if ($data['PhotoID'] == 0) {
            $this->catalogService->addPhoto($data);
        } else {
            $this->catalogService->updatePhoto($data);
        }

        header("Location: index.php?controller=product&action=edit&id=" . $data['ProductID'] . "#photos");
        exit;
    }

    public function deletePhoto($id, $photoId)
    {
        $this->catalogService->deletePhoto($photoId);
        header("Location: index.php?controller=product&action=edit&id=$id#photos");
        exit;
    }

    // ==========================================
    // API: SEARCH PRODUCT (AJAX dùng cho promotion)
    // ==========================================
    public function searchAjax()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $categoryID = (int)($_GET['categoryID'] ?? 0);

        $input = [
            'page' => 1,
            'pageSize' => 50, // giới hạn để tránh lag
            'SearchValue' => $keyword,
            'CategoryID' => $categoryID,
            'SupplierID' => 0
        ];

        $result = $this->catalogService->listProducts($input);

        header('Content-Type: application/json');
        echo json_encode($result['DataItems']);
        exit;
    }
}
