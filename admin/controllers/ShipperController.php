<?php

require_once __DIR__ . "/../../app/services/PartnerService.php";
require_once __DIR__ . "/../../app/core/AppContext.php";

class ShipperController
{
    private const SHIPPER_SEARCH_INPUT = "ShipperSearchInput";
    private $service;

    public function __construct()
    {
        $this->service = new PartnerService();
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
        $model = AppContext::getSession(self::SHIPPER_SEARCH_INPUT);

        if ($model == null) {
            $model = [
                'page' => 1,
                'pageSize' => AppContext::pageSize(),
                'SearchValue' => ''
            ];
        }

        $this->render('shipper/index', compact('model'));
    }

    // =====================
    // SEARCH
    // =====================
    public function search()
    {
        $input = [
            'page' => $_REQUEST['page'] ?? 1,
            'pageSize' => AppContext::pageSize(),
            'searchValue' => trim($_REQUEST['SearchValue'] ?? '')
        ];

        $result = $this->service->listShippers($input);

        AppContext::setSession(self::SHIPPER_SEARCH_INPUT, $input);

        include __DIR__ . '/../views/shipper/search.php';
    }

    // =====================
    // CREATE
    // =====================
    public function create()
    {
        $model = [
            'ShipperID' => 0,
            'ShipperName' => '',
            'Phone' => ''
        ];

        $this->render('shipper/edit', compact('model'));
    }

    // =====================
    // EDIT
    // =====================
    public function edit($id)
    {
        $model = $this->service->getShipper($id);

        if (!$model) {
            header("Location: index.php?controller=shipper");
            exit;
        }

        $this->render('shipper/edit', compact('model'));
    }

    // =====================
    // SAVE
    // =====================
    public function save()
    {
        $data = [
            'ShipperID' => $_POST['ShipperID'] ?? 0,
            'ShipperName' => trim($_POST['ShipperName'] ?? ''),
            'Phone' => trim($_POST['Phone'] ?? '')
        ];

        // VALIDATION
        if ($data['ShipperName'] == '' || $data['Phone'] == '') {
            $this->render('shipper/edit', ['model' => $data]);
            return;
        }

        // SAVE
        if ($data['ShipperID'] == 0) {
            $this->service->addShipper($data);
        } else {
            $this->service->updateShipper($data);
        }

        header("Location: index.php?controller=shipper");
        exit;
    }

    // =====================
    // DELETE (GET + POST)
    // =====================
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->service->deleteShipper($id);
            header("Location: index.php?controller=shipper");
            exit;
        }

        $model = $this->service->getShipper($id);

        if (!$model) {
            header("Location: index.php?controller=shipper");
            exit;
        }

        $this->render('shipper/delete', compact('model'));
    }

    public function deletePost($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->service->deleteShipper($id);
        }
        header("Location: index.php?controller=shipper");
        exit;
    }
}
