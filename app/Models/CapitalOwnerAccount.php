<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapitalOwnerAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function expense(){
        return $this->belongsTo(Expense::class,'expense_id');
    }

    public function income (){
        return $this->belongsTo(Income::class,'income_id');
    }

    public function treasury (){
        return $this->belongsTo(Treasury::class,'treasury_id');
    }

    public function user (){
        return $this->belongsTo(User::class,'user_id');
    }
}
