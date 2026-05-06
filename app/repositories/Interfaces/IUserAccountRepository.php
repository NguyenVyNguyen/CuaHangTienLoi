<?php

interface IUserAccountRepository
{
    public function authorize($userName, $password);
    public function changePassword($userName, $password);
}