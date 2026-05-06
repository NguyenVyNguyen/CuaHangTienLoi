<?php

require_once __DIR__ . "/../repositories/Implementations/OrderRepository.php";

class SalesService
{
    private $orderDB;

    public function __construct()
    {
        $this->orderDB = new OrderRepository();
    }

    // ================= ORDER =================

    public function listOrders($input)
    {
        return $this->orderDB->list($input);
    }

    public function getOrder($orderID)
    {
        return $this->orderDB->get($orderID);
    }

    public function addOrder($customerID = 0, $province = "", $address = "")
    {
        $order = (object)[
            "CustomerID" => $customerID,
            "DeliveryProvince" => $province,
            "DeliveryAddress" => $address,
            "Status" => 0,
            "OrderTime" => date("Y-m-d H:i:s")
        ];

        return $this->orderDB->add($order);
    }

    public function updateOrder($data)
    {
        return $this->orderDB->update($data);
    }

    public function deleteOrder($orderID)
    {
        return $this->orderDB->delete($orderID);
    }

    // ================= ORDER DETAILS =================

    public function listDetails($orderID)
    {
        return $this->orderDB->listDetails($orderID);
    }

    public function getDetail($orderID, $productID)
    {
        return $this->orderDB->getDetail($orderID, $productID);
    }

    public function addDetail($data)
    {
        return $this->orderDB->addDetail($data);
    }

    public function updateDetail($data)
    {
        return $this->orderDB->updateDetail($data);
    }

    public function deleteDetail($orderID, $productID)
    {
        return $this->orderDB->deleteDetail($orderID, $productID);
    }

    // ================= ORDER WORKFLOW (Quy trình xử lý đơn hàng) =================

    public function acceptOrder($orderID, $employeeID)
    {
        $order = $this->orderDB->get($orderID);
        if (!$order || $order->Status != 0) return false;

        $order->EmployeeID = $employeeID;
        $order->AcceptTime = date("Y-m-d H:i:s");
        $order->Status = 1; // Chờ giao hàng

        return $this->orderDB->update($order);
    }

    public function rejectOrder($orderID, $employeeID)
    {
        $order = $this->orderDB->get($orderID);
        if (!$order || $order->Status != 0) return false;

        $order->EmployeeID = $employeeID;
        $order->FinishedTime = date("Y-m-d H:i:s");
        $order->Status = 2; // Từ chối

        return $this->orderDB->update($order);
    }

    public function cancelOrder($orderID)
    {
        $order = $this->orderDB->get($orderID);
        if (!$order) return false;

        $order->FinishedTime = date("Y-m-d H:i:s");
        $order->Status = 3; // Hủy bỏ

        return $this->orderDB->update($order);
    }

    public function shipOrder($orderID, $shipperID)
    {
        $order = $this->orderDB->get($orderID);
        if (!$order || $order->Status != 1) return false;

        $order->ShipperID = $shipperID;
        $order->ShippedTime = date("Y-m-d H:i:s");
        $order->Status = 4; // Đang giao hàng

        return $this->orderDB->update($order);
    }

    public function completeOrder($orderID)
    {
        $order = $this->orderDB->get($orderID);
        if (!$order || $order->Status != 4) return false;

        $order->FinishedTime = date("Y-m-d H:i:s");
        $order->Status = 5; // Hoàn tất

        return $this->orderDB->update($order);
    }
}