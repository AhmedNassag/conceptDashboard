<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierExpense extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function supplier (){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function expense (){
        return $this->belongsTo(Expense::class,'expense_id');
    }

    public function treasury (){
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function purchase (){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function user (){
        return $this->belongsTo(User::class,'user_id');
    }

    public function supplierAccount (){
        return $this->hasOne(SupplierAccount::class,'supplier_expense_id');
    }
}
