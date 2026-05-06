<?php

require_once __DIR__ . '/../../app/services/CatalogService.php';
require_once __DIR__ . '/../../app/core/AppContext.php';

class CategoryController
{
    private const CATEGORY_SEARCH_INPUT = "CategorySearchInput";
    private $catalogService;

    public function __construct()
    {
        $this->catalogService = new CatalogService();
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
    // INDEX
    // =====================
    public function index()
    {
        $model = AppContext::getSession(self::CATEGORY_SEARCH_INPUT);

        if ($model == null) {
            $model = [
                'page' => 1,
                'pageSize' => AppContext::pageSize(),
                'SearchValue' => ''
            ];
        }

        $this->render('category/index', compact('model'));
    }

    // =====================
    // SEARCH
    // =====================
    public function search()
    {
        $input = [
            'page' => (int)($_REQUEST['page'] ?? 1),
            'pageSize' => (int)($_REQUEST['PageSize'] ?? AppContext::pageSize()),
            'searchValue' => trim($_REQUEST['SearchValue'] ?? '')
        ];

        $result = $this->catalogService->listCategories($input);

        // Lưu lại search
        AppContext::setSession(self::CATEGORY_SEARCH_INPUT, $input);

        include __DIR__ . '/../views/category/search.php';
    }

    // =====================
    // CREATE
    // =====================
    public function create()
    {
        $model = [
            'CategoryID' => 0,
            'CategoryName' => '',
            'Description' => ''
        ];

        $this->render('category/edit', compact('model'));
    }

    // =====================
    // EDIT
    // =====================
    public function edit($id)
    {
        $model = $this->catalogService->getCategory($id);

        if (!$model) {
            header("Location: index.php?controller=category");
            exit;
        }

        $this->render('category/edit', compact('model'));
    }

    // =====================
    // SAVE
    // =====================
    public function save()
    {
        $data = [
            'CategoryID' => $_POST['CategoryID'] ?? 0,
            'CategoryName' => trim($_POST['CategoryName'] ?? ''),
            'Description' => trim($_POST['Description'] ?? '')
        ];

        if ($data['CategoryName'] == '') {
            $this->render('category/edit', ['model' => $data]);
            return;
        }

        if ($data['CategoryID'] == 0) {
            $this->catalogService->addCategory($data);
        } else {
            $this->catalogService->updateCategory($data);
        }

        header("Location: index.php?controller=category");
        exit;
    }

    // =====================
    // DELETE
    // =====================
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->catalogService->deleteCategory($id);
            header("Location: index.php?controller=category");
            exit;
        }

        $model = $this->catalogService->getCategory($id);

        if (!$model) {
            header("Location: index.php?controller=category");
            exit;
        }

        $this->render('category/delete', compact('model'));
    }
    // =====================
    // DELETE POST
    // =====================
    public function deletePost($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->catalogService->deleteCategory($id);
        }
        header("Location: index.php?controller=category");
        exit;
    }
}
