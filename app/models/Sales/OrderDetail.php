<?php
class OrderDetail {
    public $OrderID;
    public $ProductID;
    public $Quantity;
    public $SalePrice;

    public function getTotalPrice() {
        return $this->Quantity * $this->SalePrice;
    }
}
?>