<?php

require_once __DIR__ . '/../../app/services/PartnerService.php';
require_once __DIR__ . '/../../app/core/AppContext.php';
require_once __DIR__ . '/../../app/services/SelectListHelper.php';
require_once __DIR__ . '/../../app/services/DictionaryService.php';
require_once __DIR__ . '/../../app/services/CatalogService.php';

class CustomerController
{
    private const CUSTOMER_SEARCH_INPUT = "CustomerSearchInput";
    private $partnerService;
    private $selectHelper;

    public function __construct()
    {
        $this->partnerService = new PartnerService();

        $catalogService = new CatalogService();
        $dictionaryService = new DictionaryService();

        $this->selectHelper = new SelectListHelper(
            $catalogService,
            $this->partnerService,
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
        $model = AppContext::getSession(self::CUSTOMER_SEARCH_INPUT);

        if ($model == null) {
            $model = [
                'page' => 1,
                'pageSize' => AppContext::pageSize(),
                'SearchValue' => ''
            ];
        }

        $this->render('customer/index', compact('model'));
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

        $result = $this->partnerService->listCustomers($input);


        AppContext::setSession(self::CUSTOMER_SEARCH_INPUT, $input);

        include __DIR__ . "/../views/customer/search.php";
    }

    // =====================
    // CREATE
    // =====================
    public function create()
    {
        $model = [
            'CustomerID' => 0,
            'CustomerName' => '',
            'Email' => '',
            'Province' => '',
            'ContactName' => '',
            'Phone' => '',
            'Address' => '',
            'IsLocked' => 0
        ];

        $provinces = $this->selectHelper->provinces();

        $this->render('customer/edit', compact('model', 'provinces'));
    }

    // =====================
    // EDIT
    // =====================
    public function edit($id)
    {
        $model = $this->partnerService->getCustomer($id);

        if (!$model) {
            header("Location: index.php?controller=customer");
            exit;
        }

        $provinces = $this->selectHelper->provinces();

        $this->render('customer/edit', compact('model', 'provinces'));
    }

    // =====================
    // SAVE
    // =====================
    public function save()
    {
        $data = [
            'CustomerID' => $_POST['CustomerID'] ?? 0,
            'CustomerName' => trim($_POST['CustomerName'] ?? ''),
            'Email' => trim($_POST['Email'] ?? ''),
            'Province' => $_POST['Province'] ?? '',
            'ContactName' => trim($_POST['ContactName'] ?? ''),
            'Phone' => trim($_POST['Phone'] ?? ''),
            'Address' => trim($_POST['Address'] ?? ''),
            'IsLocked' => isset($_POST['IsLocked']) ? 1 : 0
        ];

        // VALIDATION
        if ($data['CustomerName'] == '' || $data['Email'] == '' || $data['Province'] == '') {
            $provinces = $this->selectHelper->provinces(); 
            $this->render('customer/edit', [
                'model' => $data,
                'provinces' => $provinces
            ]);
            return;
        }

        if (!$this->partnerService->validateCustomerEmail($data['Email'], $data['CustomerID'])) {
            $provinces = $this->selectHelper->provinces();
            $this->render('customer/edit', [
                'model' => $data,
                'provinces' => $provinces
            ]);
            return;
        }

        // SAVE
        if ($data['CustomerID'] == 0) {
            $this->partnerService->addCustomer($data);
        } else {
            $this->partnerService->updateCustomer($data);
        }

        header("Location: index.php?controller=customer");
        exit;
    }

    // =====================
    // DELETE (GET)
    // =====================
    public function delete($id)
    {
        $model = $this->partnerService->getCustomer($id);

        if (!$model) {
            header("Location: index.php?controller=customer");
            exit;
        }

        $model['AllowDelete'] = !$this->partnerService->isUsedCustomer($id);

        $this->render('customer/delete', compact('model'));
    }

    // =====================
    // DELETE (POST)
    // =====================
    public function deletePost($id)
    {
        if ($this->partnerService->isUsedCustomer($id)) {
            header("Location: index.php?controller=customer&action=delete&id=$id");
            exit;
        }

        $this->partnerService->deleteCustomer($id);

        header("Location: index.php?controller=customer");
        exit;
    }

    // =====================
    // CHANGE PASSWORD
    // =====================
    public function changePassword($id)
    {
        $model = $this->partnerService->getCustomer($id);

        if (!$model) {
            header("Location: index.php?controller=customer");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['NewPassword'] ?? '';
            $confirmPassword = $_POST['ConfirmPassword'] ?? '';

            if ($newPassword != '' && $newPassword === $confirmPassword && strlen($newPassword) >= 6) {
                $this->partnerService->changeCustomerPassword($id, $newPassword);
                header("Location: index.php?controller=customer");
                exit;
            }
        }

        $this->render('customer/changepassword', compact('model'));
    }
}
