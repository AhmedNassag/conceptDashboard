<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierIncome extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function supplier (){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function income (){
        return $this->belongsTo(Income::class,'income_id');
    }

    public function treasury (){
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function purchaseReturn (){
        return $this->belongsTo(PurchaseReturn::class,'purchase_return_id');
    }

    public function user (){
        return $this->belongsTo(User::class,'user_id');
    }

    public function SupplierAccount (){
        return $this->hasOne(SupplierAccount::class,'supplier_income_id');
    }
}
