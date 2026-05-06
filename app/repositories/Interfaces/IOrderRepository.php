<?php

interface IOrderRepository
{
    public function list($input);
    public function get($orderID);

    public function add($data);
    public function update($data);
    public function delete($orderID);

    public function listDetails($orderID);
    public function getDetail($orderID, $productID);

    public function addDetail($data);
    public function updateDetail($data);
    public function deleteDetail($orderID, $productID);
}