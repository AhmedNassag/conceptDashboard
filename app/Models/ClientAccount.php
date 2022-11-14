<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function purchaseReturn (){
        return $this->belongsTo(PurchaseReturn::class,'purchase_return_id');
    }

    public function clientExpense (){
        return $this->belongsTo(ClientExpense::class,'client_expense_id');
    }

    public function clientIncome (){
        return $this->belongsTo(ClientIncome::class,'client_income_id');
    }
}
