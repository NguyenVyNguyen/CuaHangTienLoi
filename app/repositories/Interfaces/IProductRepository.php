<?php

interface IProductRepository
{
    public function list($input);
    public function get($productID);

    public function add($data);
    public function update($data);
    public function delete($productID);
    public function isUsed($productID);

    // Attributes
    public function listAttributes($productID);
    public function getAttribute($attributeID);
    public function addAttribute($data);
    public function updateAttribute($data);
    public function deleteAttribute($attributeID);

    // Photos
    public function listPhotos($productID);
    public function getPhoto($photoID);
    public function addPhoto($data);
    public function updatePhoto($data);
    public function deletePhoto($photoID);
}