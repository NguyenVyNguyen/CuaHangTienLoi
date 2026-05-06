<?php

require_once __DIR__ . "/../repositories/implementations/EmployeeRepository.php";

class HRService
{
    private $employeeDB;

    public function __construct()
    {
        $this->employeeDB = new EmployeeRepository();
    }

    public function listEmployees($input)
    {
        // Ép kiểu object nếu đầu vào là array để đồng bộ với Repository
        $inputObj = is_array($input) ? (object)$input : $input;
        return $this->employeeDB->list($inputObj);
    }

    public function getEmployee($id)
    {
        return $this->employeeDB->get($id);
    }

    public function addEmployee($data)
    {
        $dataObj = is_array($data) ? (object)$data : $data;
        return $this->employeeDB->add($dataObj);
    }

    public function updateEmployee($data)
    {
        $dataObj = is_array($data) ? (object)$data : $data;
        return $this->employeeDB->update($dataObj);
    }

    public function deleteEmployee($id)
    {
        // Kiểm tra ràng buộc dữ liệu trước khi xóa
        if ($this->employeeDB->isUsed($id)) {
            return false;
        }
        return $this->employeeDB->delete($id);
    }

    public function isUsed($id)
    {
        return $this->employeeDB->isUsed($id);
    }

    public function validateEmail($email, $id = 0)
    {
        return $this->employeeDB->validateEmail($email, $id);
    }

    public function changePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->employeeDB->updatePassword($id, $hashedPassword);
    }

    public function updateRoles($id, $roleNames)
    {
        return $this->employeeDB->updateRoles($id, $roleNames);
    }
}
