<?php
require_once "PaginationSearchInput.php";

class ProductSearchInput extends PaginationSearchInput {
    public $CategoryID = 0;
    public $SupplierID = 0;
    public $MinPrice = 0;
    public $MaxPrice = 0;
}
?>