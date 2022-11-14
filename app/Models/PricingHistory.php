<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingHistory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function sellingMethod(){
        return $this->belongsTo(SellingMethod::class,'selling_method_id');
    }

    public function measurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'measurement_unit_id');
    }
}
