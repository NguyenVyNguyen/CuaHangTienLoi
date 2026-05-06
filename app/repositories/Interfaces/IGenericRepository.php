<?php

interface IGenericRepository
{
    public function list($input);
    public function get($id);
    public function add($data);
    public function update($data);
    public function delete($id);
    public function isUsed($id);
}