<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'sum_account'
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    public function getSumAccountAttribute(): float
    {
        return $this->supplierAccounts->sum('amount');
    }


    public function purchases(){

        return $this->hasMany(Purchase::class);
    }

    public function purchaseReturns(){
        return $this->hasMany(PurchaseReturn::class);
    }

    public function supplierAccounts(){
        return $this->hasMany(SupplierAccount::class);
    }

    public function supplierExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierExpense::class);
    }

    public function supplierIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierIncome::class);
    }

}
