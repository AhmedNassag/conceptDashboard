<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientIncome extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function client (){
        return $this->belongsTo(User::class,'client_id');
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

    public function clientAccount (){
        return $this->hasOne(ClientAccount::class,'client_income_id');
    }

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
}
