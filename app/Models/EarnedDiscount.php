<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarnedDiscount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }
}
