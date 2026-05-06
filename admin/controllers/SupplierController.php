<?php

require_once __DIR__ . '/../../app/services/PartnerService.php';
require_once __DIR__ . '/../../app/services/DictionaryService.php';
require_once __DIR__ . '/../../app/services/CatalogService.php';
require_once __DIR__ . '/../../app/services/SelectListHelper.php';
require_once __DIR__ . '/../../app/core/AppContext.php';

class SupplierController
{
    private const SUPPLIER_SEARCH_INPUT = "SupplierSearchInput";

    private $service;
    private $selectHelper;

    public function __construct()
    {
        $this->service = new PartnerService();

        $catalogService = new CatalogService();
        $dictionaryService = new DictionaryService();

        $this->selectHelper = new SelectListHelper(
            $catalogService,
            $this->service,
            $dictionaryService
        );
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
        $model = AppContext::getSession(self::SUPPLIER_SEARCH_INPUT);

        if ($model == null) {
            $model = [
                'page' => 1,
                'pageSize' => AppContext::pageSize(),
                'SearchValue' => ''
            ];
        }

        $this->render('supplier/index', compact('model'));
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

        $result = $this->service->listSuppliers($input);

        AppContext::setSession(self::SUPPLIER_SEARCH_INPUT, $input);

        include __DIR__ . '/../views/supplier/search.php';
    }

    // =====================
    // CREATE
    // =====================
    public function create()
    {
        $model = [
            'SupplierID' => 0,
            'SupplierName' => '',
            'Email' => '',
            'Province' => '',
            'ContactName' => '',
            'Phone' => '',
            'Address' => ''
        ];

        $provinces = $this->selectHelper->provinces();

        $this->render('supplier/edit', compact('model', 'provinces'));
    }

    // =====================
    // EDIT
    // =====================
    public function edit($id)
    {
        $model = $this->service->getSupplier($id);

        if (!$model) {
            header("Location: index.php?controller=supplier");
            exit;
        }

        $provinces = $this->selectHelper->provinces();

        $this->render('supplier/edit', compact('model', 'provinces'));
    }

    // =====================
    // SAVE
    // =====================
    public function save()
    {
        $data = [
            'SupplierID' => $_POST['SupplierID'] ?? 0,
            'SupplierName' => trim($_POST['SupplierName'] ?? ''),
            'Email' => trim($_POST['Email'] ?? ''),
            'Province' => $_POST['Province'] ?? '',
            'ContactName' => $_POST['ContactName'] ?? '',
            'Phone' => $_POST['Phone'] ?? '',
            'Address' => $_POST['Address'] ?? ''
        ];

        $errors = [];

        // VALIDATION
        if ($data['SupplierName'] == '') {
            $errors[] = "Tên nhà cung cấp không được để trống";
        }

        if ($data['Email'] == '') {
            $errors[] = "Email không được để trống";
        }

        if ($data['Province'] == '') {
            $errors[] = "Vui lòng chọn tỉnh/thành";
        }

        // ERROR → render lại form
        if (!empty($errors)) {
            $provinces = $this->selectHelper->provinces();
            $this->render('supplier/edit', [
                'model' => $data,
                'provinces' => $provinces,
                'errors' => $errors
            ]);
            return;
        }

        // SAVE
        if ($data['SupplierID'] == 0) {
            $this->service->addSupplier($data);
        } else {
            $this->service->updateSupplier($data);
        }

        header("Location: index.php?controller=supplier");
        exit;
    }

    // =====================
    // DELETE (GET)
    // =====================
    public function delete($id)
    {
        $model = $this->service->getSupplier($id);

        if (!$model) {
            header("Location: index.php?controller=supplier");
            exit;
        }

        $model['AllowDelete'] = !$this->service->isUsedSupplier($id);

        $this->render('supplier/delete', compact('model'));
    }

    // =====================
    // DELETE (POST)
    // =====================
    public function deletePost($id)
    {
        if ($this->service->isUsedSupplier($id)) {
            header("Location: index.php?controller=supplier&action=delete&id=$id");
            exit;
        }

        $this->service->deleteSupplier($id);

        header("Location: index.php?controller=supplier");
        exit;
    }
}