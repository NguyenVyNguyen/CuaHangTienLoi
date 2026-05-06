<?php

require_once __DIR__ . '/../../app/services/CatalogService.php';
require_once __DIR__ . '/../../app/core/AppContext.php';

class PromotionController
{
    private const SEARCH_INPUT = "PromotionSearchInput";
    private $service;

    public function __construct()
    {
        $this->service = new CatalogService();
    }

    private function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/' . $view . '.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    public function index()
    {
        $model = AppContext::getSession(self::SEARCH_INPUT);

        if ($model == null) {
            $model = [
                'page' => 1,
                'pageSize' => AppContext::pageSize(),
                'SearchValue' => ''
            ];
        }

        $this->render('promotion/index', compact('model'));
    }

    public function search()
    {
        $this->service->disableExpiredPromotions();

        $input = [
            'page' => (int)($_REQUEST['page'] ?? 1),
            'pageSize' => (int)($_REQUEST['PageSize'] ?? AppContext::pageSize()),
            'searchValue' => trim($_REQUEST['SearchValue'] ?? '')
        ];

        $result = $this->service->listPromotions($input);

        AppContext::setSession(self::SEARCH_INPUT, $input);

        include __DIR__ . '/../views/promotion/search.php';
    }

    public function detail($id)
    {
        $promotion = $this->service->getPromotion($id);

        if (!$promotion) {
            header("Location: index.php?controller=promotion");
            exit;
        }

        $targets = $this->service->getPromotionTargets($id);

        $this->render('promotion/detail', compact('promotion', 'targets'));
    }

    // CREATE
    public function create()
    {
        $model = [
            'PromotionID' => 0,
            'PromotionName' => '',
            'StartDate' => '',
            'EndDate' => '',
            'IsActive' => 1,
            'DiscountType' => 1,
            'DiscountValue' => 0,
            'ApplyType' => 1
        ];

        $categories = $this->service->listCategories(['page' => 1, 'pageSize' => 1000])['DataItems'];
        $products   = $this->service->listProducts(['page' => 1, 'pageSize' => 1000])['DataItems'];

        $this->render('promotion/edit', compact('model', 'categories', 'products'));
    }

    // EDIT
    public function edit($id)
    {
        $model = $this->service->getPromotion($id);

        if (!$model) {
            header("Location: index.php?controller=promotion");
            exit;
        }

        $categories = $this->service->listCategories(['page' => 1, 'pageSize' => 1000])['DataItems'];
        $products   = $this->service->listProducts(['page' => 1, 'pageSize' => 1000])['DataItems'];

        $this->render('promotion/edit', compact('model', 'categories', 'products'));
    }

    // SAVE
    public function save()
    {
        $data = [
            'PromotionID' => $_POST['PromotionID'] ?? 0,
            'PromotionName' => trim($_POST['PromotionName'] ?? ''),
            'StartDate' => $_POST['StartDate'] ?? null,
            'EndDate' => $_POST['EndDate'] ?? null,
            'DiscountType' => $_POST['DiscountType'] ?? 1,
            'DiscountValue' => $_POST['DiscountValue'] ?? 0,
            'IsActive' => isset($_POST['IsActive']) ? 1 : 0,
            'ApplyType' => $_POST['ApplyType'] ?? 1
        ];

        // ❗ VALIDATE
        if ($data['PromotionName'] == '') {
            $this->render('promotion/edit', ['model' => $data]);
            return;
        }

        if ($data['StartDate'] && $data['EndDate']) {
            if (strtotime($data['EndDate']) <= strtotime($data['StartDate'])) {
                die("Ngày kết thúc phải lớn hơn ngày bắt đầu");
            }
        }

        // SAVE
        if ($data['PromotionID'] == 0) {
            $promotionID = $this->service->addPromotion($data);
        } else {
            $this->service->updatePromotion($data);
            $promotionID = $data['PromotionID'];
        }

        // 🔥 LƯU APPLY
        $productIDs = $_POST['ProductIDs'] ?? [];
        $categoryIDs = [];

        if (!empty($_POST['CategoryID']) && $_POST['CategoryID'] != 0) {
            $categoryIDs[] = $_POST['CategoryID'];
        }

        // 🔥 Lưu BOTH luôn
        $this->service->savePromotionTargets($promotionID, $productIDs, $categoryIDs);

        header("Location: index.php?controller=promotion");
        exit;
    }

    // DELETE
    public function delete($id)
    {
        $model = $this->service->getPromotion($id);

        if (!$model) {
            header("Location: index.php?controller=promotion");
            exit;
        }

        $this->render('promotion/delete', compact('model'));
    }

    // DELETE POST
    public function deletePost($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->service->deletePromotion($id);
        }

        header("Location: index.php?controller=promotion");
        exit;
    }
}
