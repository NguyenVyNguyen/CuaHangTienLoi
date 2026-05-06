<?php

require_once __DIR__ . "/../repositories/Implementations/ProvinceRepository.php";

class DictionaryService
{
    private $provinceDB;

    public function __construct()
    {
        $this->provinceDB = new ProvinceRepository();
    }

    public function listProvinces()
    {
        return $this->provinceDB->list();
    }
}