<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStoreProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function orderDetails(){
        return $this->belongsTo(OrderDetails::class,'order_details_id');
    }

    public function  storeProduct()
    {
        return $this->belongsTo(StoreProduct::class,'store_product_id');
    }

    public function measurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'measurement_unit_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
