<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    protected $appends = [
        'main_quantity',
    ];

    // يجب تجميع الكميات المباعه بالنسبة للوحده الرئيسية

    public function getMainQuantityAttribute(){
        return floor($this->sub_quantity_order / $this->count_unit);
    }

//    public function getSubQuantityAttribute(){
//        $total_sub_quantity = $this->quantity * $this->count_unit ;
//        $quantity_main_in_order = $this->orderProduct()->where('measurement_unit_id',$this->main_measurement_unit_id)->sum('amount') * $this->count_unit;
//        $quantity_sub_in_order = $this->orderProduct()->where('measurement_unit_id',$this->sub_measurement_unit_id)->sum('amount');
//        $total_quantity = $total_sub_quantity - ( $quantity_main_in_order + $quantity_sub_in_order ) ;
//        return $total_quantity;
//    }

    public function setSubQuantityOrderAttribute($value = 0)
    {
        $total_sub_quantity = $this->quantity * $this->count_unit ;
        $quantity_main_in_order = $this->orderProduct()->where('measurement_unit_id',$this->main_measurement_unit_id)->sum('quantity') * $this->count_unit;
        $quantity_sub_in_order = $this->orderProduct()->where('measurement_unit_id',$this->sub_measurement_unit_id)->sum('quantity');

        $total_return_main = 0;
        $total_return_sub = 0;
        if ($this->purchase_product_id != null){
            $total_return_main += $this->purchaseProduct->returnProducts()->where('return_status',1)->sum('quantity') *  $this->product->count_unit;
            $total_return_sub += $this->purchaseProduct->returnProducts()->where('return_status',1)->sum('sub_quantity');
        }else{
            $total_return_main += $this->product->returnProducts()->where('return_status',1)->sum('quantity') * $this->product->count_unit;
            $total_return_sub += $this->product->returnProducts()->where('return_status',1)->sum('sub_quantity');
        }

        $value = $total_sub_quantity - ( $quantity_main_in_order + $quantity_sub_in_order + $total_return_main + $total_return_sub) ;

        $this->attributes['sub_quantity_order'] = $value + $this->sub_quantity;
    }

    public function examinationRecord(){
        return $this->belongsTo(ExaminationRecord::class,'examination_record_id');
    }

    public function productStatus(){
        return $this->belongsTo(ProductStatus::class,'product_status_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }

    public function purchaseProduct(){
        return $this->belongsTo(PurchaseProduct::class,'purchase_product_id');
    }

    public function mainMeasurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'main_measurement_unit_id');
    }

    public function subMeasurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'sub_measurement_unit_id');
    }

    public function orderProduct(){
        return $this->hasMany(OrderStoreProduct::class);
    }
}
