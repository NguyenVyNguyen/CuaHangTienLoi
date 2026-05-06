<?php

require_once "IGenericRepository.php";

interface ICustomerRepository extends IGenericRepository
{
    public function validateEmail($email, $id = 0);
}