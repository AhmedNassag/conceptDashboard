<?php

namespace App\Interfaces;

interface OwnerAccountRepositoryInterface
{
    public function getAllOwnerAccount($request);
    public function getOwnerAccountById($id);
    public function deleteOwnerAccount($id);
    public function createOwnerAccount(array $orderDetails);
    public function updateOwnerAccount($id, array $newDetails);
}
