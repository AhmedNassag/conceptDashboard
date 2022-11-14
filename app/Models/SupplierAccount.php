<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //relations

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function purchaseReturn (){
        return $this->belongsTo(PurchaseReturn::class,'purchase_return_id');
    }

    public function supplierExpense (){
        return $this->belongsTo(SupplierExpense::class,'supplier_expense_id');
    }

    public function supplierIncome (){
        return $this->belongsTo(SupplierIncome::class,'supplier_income_id');
    }
}
