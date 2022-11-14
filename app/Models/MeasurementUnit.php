<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'integer'
    ];

    public function mainproducts(){
        return $this->hasMany(Product::class,'main_measurement_unit_id');
    }
    public function subproducts(){
        return $this->hasMany(Product::class,'sub_measurement_unit_id');
    }

    public function ProductPricing(){
        return $this->hasMany(ProductPricing::class,'measurement_unit_id');
    }

    public function pricingHistories(){
        return $this->hasMany(PricingHistory::class);
    }

    public function mainStoreProduct(){
        return $this->hasMany(StoreProduct::class,'main_measurement_unit_id');
    }

    public function subStoreProduct(){
        return $this->hasMany(StoreProduct::class,'sub_measurement_unit_id');
    }

    public function mainOrderDetails(){
        return $this->hasMany(OrderDetails::class,'main_measurement_unit_id');
    }

    public function subOrderDetails(){
        return $this->hasMany(OrderDetails::class,'sub_measurement_unit_id');
    }

    public function orderStoreProduct(){
        return $this->hasMany(OrderStoreProduct::class,'measurement_unit_id');
    }

    public function mainOrderRetuen(){
        return $this->hasMany(OrderRetuen::class,'main_measurement_unit_id');
    }

    public function subOrderRetuen(){
        return $this->hasMany(OrderRetuen::class,'sub_measurement_unit_id');
    }
}
