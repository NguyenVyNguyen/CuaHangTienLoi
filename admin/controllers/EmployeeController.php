<?php

require_once __DIR__ . '/../../app/services/HRService.php';
require_once __DIR__ . '/../../app/core/AppContext.php';

class EmployeeController
{
    private const EMPLOYEE_SEARCH_INPUT = "EmployeeSearchInput";
    private $hrService;

    public function __construct()
    {
        $this->hrService = new HRService();
    }

    // =====================
    // RENDER (giống Category)
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
        $model = AppContext::getSession(self::EMPLOYEE_SEARCH_INPUT);

        if ($model == null) {
            $model = [
                'page' => 1,
                'pageSize' => AppContext::pageSize(),
                'SearchValue' => ''
            ];
        }

        $this->render('employee/index', compact('model'));
    }

    // =====================
    // SEARCH
    // =====================
    public function search()
    {
        $input = [
            "page" => (int)($_GET['page'] ?? 1),
            "pageSize" => AppContext::pageSize(),
            "searchValue" => trim($_GET['SearchValue'] ?? '')
        ];

        $input['offset'] = ($input['page'] - 1) * $input['pageSize'];

        $result = $this->hrService->listEmployees($input);

        // ===== THÊM ĐOẠN NÀY =====
        $totalRecords = $result['total'];
        $totalPages = ceil($totalRecords / $input['pageSize']);

        $pages = [];
        for ($i = 1; $i <= $totalPages; $i++) {
            $pages[] = [
                'Page' => $i,
                'IsCurrent' => $i == $input['page']
            ];
        }

        $result['pages'] = $pages;
        // =========================

        AppContext::setSession(self::EMPLOYEE_SEARCH_INPUT, $input);

        include __DIR__ . "/../views/employee/search.php";
    }

    // =====================
    // CREATE
    // =====================
    public function create()
    {
        $model = [
            "EmployeeID" => 0,
            "FullName" => "",
            "BirthDate" => "",
            "Address" => "",
            "Phone" => "",
            "Email" => "",
            "Photo" => "nophoto.png",
            "IsWorking" => 1
        ];

        $this->render('employee/edit', compact('model'));
    }

    // =====================
    // EDIT
    // =====================
    public function edit($id)
    {
        $model = $this->hrService->getEmployee($id);

        if (!$model) {
            header("Location: index.php?controller=employee");
            exit;
        }

        $this->render('employee/edit', compact('model'));
    }

    // =====================
    // SAVE
    // =====================
    public function save()
    {
        $data = (object)[
            "EmployeeID" => $_POST["EmployeeID"] ?? 0,
            "FullName" => trim($_POST["FullName"] ?? ""),
            "BirthDate" => $_POST["BirthDate"] ?? null,
            "Address" => trim($_POST["Address"] ?? ""),
            "Phone" => trim($_POST["Phone"] ?? ""),
            "Email" => trim($_POST["Email"] ?? ""),
            "IsWorking" => isset($_POST["IsWorking"]) ? 1 : 0,
            "Photo" => $_POST["CurrentPhoto"] ?? "nophoto.png"
        ];

        $errors = [];

        if ($data->FullName == "") {
            $errors[] = "Vui lòng nhập họ tên";
        }

        if ($data->Email == "") {
            $errors[] = "Vui lòng nhập email";
        }

        if (!$this->hrService->validateEmail($data->Email, $data->EmployeeID)) {
            $errors[] = "Email đã tồn tại";
        }

        // lỗi → render lại
        if (!empty($errors)) {
            $this->render('employee/edit', [
                'model' => $data,
                'errors' => $errors
            ]);
            return;
        }

        // upload
        if (!empty($_FILES["uploadPhoto"]["tmp_name"])) {

            $file = $_FILES["uploadPhoto"];
            $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            $fileName = uniqid() . "." . $ext;

            $path = __DIR__ . '/../images/employees/';

            if (!is_dir($path)) mkdir($path, 0777, true);

            if (move_uploaded_file($file["tmp_name"], $path . $fileName)) {

                $data->Photo = $fileName;
            }
        }

        // save
        if ($data->EmployeeID == 0) {
            $this->hrService->addEmployee($data);
        } else {
            $this->hrService->updateEmployee($data);
        }

        header("Location: index.php?controller=employee");
        exit;
    }

    // =====================
    // DELETE
    // =====================
    public function delete($id)
    {
        $model = $this->hrService->getEmployee($id);

        if (!$model) {
            header("Location: index.php?controller=employee");
            exit;
        }

        $model['AllowDelete'] = !$this->hrService->isUsed($id);

        $this->render('employee/delete', compact('model'));
    }

    public function deletePost($id)
    {
        if ($this->hrService->isUsed($id)) {
            header("Location: index.php?controller=employee&action=delete&id=$id");
            exit;
        }

        $this->hrService->deleteEmployee($id);

        header("Location: index.php?controller=employee");
        exit;
    }

    // =====================
    // CHANGE PASSWORD
    // =====================
    public function changePassword($id)
    {
        $model = $this->hrService->getEmployee($id);

        if (!$model) {
            header("Location: index.php?controller=employee");
            exit;
        }

        $this->render('employee/changepassword', compact('model'));
    }

    public function changePasswordPost()
    {
        $id = $_POST['id'] ?? 0;
        $newPassword = $_POST['newPassword'] ?? "";
        $confirm = $_POST['confirmPassword'] ?? "";

        $errors = [];

        if ($newPassword == "") {
            $errors[] = "Vui lòng nhập mật khẩu";
        }

        if ($newPassword != $confirm) {
            $errors[] = "Mật khẩu xác nhận không đúng";
        }

        if (!empty($errors)) {
            $model = $this->hrService->getEmployee($id);

            $this->render('employee/changepassword', [
                'model' => $model,
                'errors' => $errors
            ]);
            return;
        }

        // hash password
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);

        $this->hrService->changePassword($id, $hashed);

        $model = $this->hrService->getEmployee($id);

        $this->render('employee/changepassword', [
            'model' => $model,
            'success' => "Đổi mật khẩu thành công"
        ]);
    }

    // =====================
    // CHANGE ROLE
    // =====================
    public function changeRole($id)
    {
        $model = $this->hrService->getEmployee($id);

        if (!$model) {
            header("Location: index.php?controller=employee");
            exit;
        }

        // ✅ ROLE MỚI (theo yêu cầu bạn)
        $allRoles = [
            "ADMIN",   // quản lý hệ thống
            "SALE",    // bán hàng
            "WAREHOUSE" // kho
        ];

        // ✅ convert string → array
        $currentRoles = !empty($model->RoleNames)
            ? explode(",", $model->RoleNames)
            : [];

        $this->render('employee/changerole', [
            'model' => $model,
            'allRoles' => $allRoles,
            'currentRoles' => $currentRoles
        ]);
    }

    public function saveRole()
    {
        $id = $_POST['id'] ?? 0;
        $roles = $_POST['roles'] ?? [];

        $roleString = implode(",", $roles);

        $this->hrService->updateRoles($id, $roleString);

        header("Location: index.php?controller=employee");
        exit;
    }
}
