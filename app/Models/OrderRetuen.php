<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRetuen extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function  sellingMethod()
    {
        return $this->belongsTo(SellingMethod::class,'selling_method_id');
    }

    public function mainMeasurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'main_measurement_unit_id');
    }

    public function subMeasurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'sub_measurement_unit_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function user()
    {
        return $this->order->user();
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function orderDetails(){
        return $this->belongsTo(OrderDetails::class,'order_details_id');
    }
}
