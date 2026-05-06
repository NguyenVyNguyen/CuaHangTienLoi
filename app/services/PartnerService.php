<?php

require_once __DIR__ . "/../repositories/Implementations/SupplierRepository.php";
require_once __DIR__ . "/../repositories/Implementations/ShipperRepository.php";
require_once __DIR__ . "/../repositories/Implementations/CustomerRepository.php";

class PartnerService
{
    private $supplierDB;
    private $shipperDB;
    private $customerDB;

    /**
     * Hàm khởi tạo - Sẽ tự động chạy khi bạn dùng 'new PartnerService()'
     */
    public function __construct()
    {
        $this->supplierDB = new SupplierRepository();
        $this->shipperDB = new ShipperRepository();
        $this->customerDB = new CustomerRepository();
    }

    // ================= SUPPLIER =================

    public function listSuppliers($input)
    {
        return $this->supplierDB->list($input);
    }
    public function getSupplier($id)
    {
        return $this->supplierDB->get($id);
    }

    public function addSupplier($data)
    {
        return $this->supplierDB->add($data);
    }

    public function updateSupplier($data)
    {
        return $this->supplierDB->update($data);
    }

    public function deleteSupplier($id)
    {
        if ($this->supplierDB->isUsed($id)) return false;
        return $this->supplierDB->delete($id);
    }

    public function isUsedSupplier($id)
    {
        return $this->supplierDB->isUsed($id);
    }

    // ================= SHIPPER =================

    public function listShippers($input)
    {
        return $this->shipperDB->list($input);
    }

    public function getShipper($id)
    {
        return $this->shipperDB->get($id);
    }

    public function addShipper($data)
    {
        return $this->shipperDB->add($data);
    }

    public function updateShipper($data)
    {
        return $this->shipperDB->update($data);
    }

    public function deleteShipper($id)
    {
        if ($this->shipperDB->isUsed($id)) return false;
        return $this->shipperDB->delete($id);
    }

    public function isUsedShipper($id)
    {
        return $this->shipperDB->isUsed($id);
    }

    // ================= CUSTOMER =================

    public function listCustomers($input)
    {
        return $this->customerDB->list($input);
    }

    public function getCustomer($id)
    {
        return $this->customerDB->get($id);
    }

    public function addCustomer($data)
    {
        return $this->customerDB->add($data);
    }

    public function updateCustomer($data)
    {
        return $this->customerDB->update($data);
    }

    public function deleteCustomer($id)
    {
        if ($this->customerDB->isUsed($id)) return false;
        return $this->customerDB->delete($id);
    }

    public function isUsedCustomer($id)
    {
        return $this->customerDB->isUsed($id);
    }

    public function validateCustomerEmail($email, $id = 0)
    {
        return $this->customerDB->validateEmail($email, $id);
    }

    public function changeCustomerPassword($id, $newPassword)
    {

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        return $this->customerDB->updatePassword($id, $hashedPassword);
    }
}
