<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function returnProducts(){
        return $this->hasMany(ReturnProduct::class);
    }

    public function storeProducts(){
        return $this->hasMany(StoreProduct::class);
    }

    public function earnedDiscount(){
        return $this->hasOne(EarnedDiscount::class);
    }

}
