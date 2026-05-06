<?php

require_once __DIR__ . "/../repositories/Implementations/CategoryRepository.php";
require_once __DIR__ . "/../repositories/Implementations/ProductRepository.php";
require_once __DIR__ . "/../repositories/Implementations/PromotionRepository.php";

class CatalogService
{
    private $categoryDB;
    private $productDB;
    private $promotionDB;

    public function __construct()
    {
        $this->categoryDB = new CategoryRepository();
        $this->productDB = new ProductRepository();
        $this->promotionDB = new PromotionRepository();
    }

    // ================= CATEGORY =================

    public function listCategories($input)
    {
        return $this->categoryDB->list($input);
    }

    public function getCategory($id)
    {
        return $this->categoryDB->get($id);
    }

    public function addCategory($data)
    {
        return $this->categoryDB->add($data);
    }

    public function updateCategory($data)
    {
        return $this->categoryDB->update($data);
    }

    public function deleteCategory($id)
    {
        // Kiểm tra nếu đang được sử dụng thì không cho xóa
        if ($this->categoryDB->isUsed($id)) {
            return false;
        }
        return $this->categoryDB->delete($id);
    }

    public function isUsedCategory($id)
    {
        return $this->categoryDB->isUsed($id);
    }

    // ================= PRODUCT =================

    public function listProducts($input)
    {
        $result = $this->productDB->list($input);

        foreach ($result["DataItems"] as &$item) {

            $promotion = $this->promotionDB->getBestPromotionForProduct($item['ProductID']);

            if ($promotion) {
                if ($promotion['DiscountType'] == 1) {
                    $item['FinalPrice'] = $item['Price'] * (1 - $promotion['DiscountValue'] / 100);
                } else {
                    $item['FinalPrice'] = $item['Price'] - $promotion['DiscountValue'];
                }

                $item['PromotionName'] = $promotion['PromotionName'];
            } else {
                $item['FinalPrice'] = $item['Price'];
            }
        }

        return $result;
    }

    public function getProduct($id)
    {
        return $this->productDB->get($id);
    }

    public function addProduct($data)
    {
        return $this->productDB->add($data);
    }

    public function updateProduct($data)
    {
        return $this->productDB->update($data);
    }

    public function deleteProduct($id)
    {
        if ($this->productDB->isUsed($id)) {
            return false;
        }
        return $this->productDB->delete($id);
    }

    public function isUsedProduct($id)
    {
        return $this->productDB->isUsed($id);
    }

    // ================= ATTRIBUTES =================

    public function listAttributes($productID)
    {
        return $this->productDB->listAttributes($productID);
    }

    public function getAttribute($id)
    {
        return $this->productDB->getAttribute($id);
    }

    public function addAttribute($data)
    {
        return $this->productDB->addAttribute($data);
    }

    public function updateAttribute($data)
    {
        return $this->productDB->updateAttribute($data);
    }

    public function deleteAttribute($id)
    {
        return $this->productDB->deleteAttribute($id);
    }

    // ================= PHOTOS =================

    public function listPhotos($productID)
    {
        return $this->productDB->listPhotos($productID);
    }

    public function getPhoto($id)
    {
        return $this->productDB->getPhoto($id);
    }

    public function addPhoto($data)
    {
        return $this->productDB->addPhoto($data);
    }

    public function updatePhoto($data)
    {
        return $this->productDB->updatePhoto($data);
    }

    public function deletePhoto($id)
    {
        return $this->productDB->deletePhoto($id);
    }
    // ================= PROMOTIONS =================
    public function listPromotions($input)
    {
        return $this->promotionDB->list($input);
    }

    public function getPromotion($id)
    {
        return $this->promotionDB->get($id);
    }

    public function getPromotionTargets($promotionID)
    {
        return $this->promotionDB->getTargets($promotionID);
    }

    public function addPromotion($data)
    {
        return $this->promotionDB->add($data);
    }

    public function updatePromotion($data)
    {
        return $this->promotionDB->update($data);
    }

    public function deletePromotion($id)
    {
        // Kiểm tra nếu đang được sử dụng thì không cho xóa
        if ($this->promotionDB->isUsed($id)) {
            return false;
        }
        return $this->promotionDB->delete($id);
    }

    public function isUsedPromotion($id)
    {
        return $this->promotionDB->isUsed($id);
    }

    public function savePromotionTargets($promotionID, $productIDs, $categoryIDs)
{
    $this->promotionDB->clearTargets($promotionID);

    // lưu product
    foreach ($productIDs as $pid) {
        $this->promotionDB->addProductTarget($promotionID, $pid);
    }

    // lưu category
    foreach ($categoryIDs as $cid) {
        $this->promotionDB->addCategoryTarget($promotionID, $cid);
    }
}

    public function disableExpiredPromotions()
    {
        return $this->promotionDB->disableExpired();
    }
}
