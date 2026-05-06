<?php

require_once "IGenericRepository.php";

interface IEmployeeRepository extends IGenericRepository
{
    public function validateEmail($email, $id = 0);
}