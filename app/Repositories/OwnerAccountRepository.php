<?php

namespace App\Repositories;

use App\Interfaces\OwnerAccountRepositoryInterface;
use App\Models\CapitalOwnerAccount;

class OwnerAccountRepository implements OwnerAccountRepositoryInterface
{
    public function getAllOwnerAccount($request)
    {
        return CapitalOwnerAccount::when($request->search,function ($q) use($request){
            return $q->where('name','like','%'.$request->search.'%')
                ->orWhere('amount','like','%'.$request->search.'%');
        })->latest()->paginate(10);
    }

    public function getOwnerAccountById($id)
    {
        return CapitalOwnerAccount::findOrFail($id);
    }

    public function deleteOwnerAccount($id)
    {
        CapitalOwnerAccount::destroy($id);
    }

    public function createOwnerAccount(array $orderDetails)
    {
         CapitalOwnerAccount::create($orderDetails);
    }

    public function updateOwnerAccount($id, array $newDetails)
    {
        return CapitalOwnerAccount::whereId($id)->update($newDetails);
    }
}
