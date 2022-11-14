<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientExpense extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function client (){
        return $this->belongsTo(User::class,'client_id');
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

    public function clientAccount (){
        return $this->hasOne(ClientAccount::class,'client_expense_id');
    }
}
