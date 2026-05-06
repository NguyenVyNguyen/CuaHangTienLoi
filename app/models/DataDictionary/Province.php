<?php

class Province
{
    public string $ProvinceName = "";
    public ?string $ProvinceID = null;

    public function __construct($data = null)
    {
        if ($data) {
            $this->ProvinceName = $data->ProvinceName ?? "";
            $this->ProvinceID = $data->ProvinceID ?? null;
        }
    }
}