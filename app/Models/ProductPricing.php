<?php

namespace App\Models;

use App\Traits\StoreTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
    use HasFactory;
    use StoreTrait;

    protected $guarded = ['id'];
    protected $appends = ['available_quantity'];

    protected $casts = [
        'active' => 'integer',
    ];

    public function getAvailableQuantityAttribute(){
        $store_id = $this->store();
        $store_main_measurement = $this->product->storeProducts()->where([
            ['store_id',$store_id],
            ['main_measurement_unit_id',$this->measurement_unit_id],
        ])->get();
        if (count($store_main_measurement) > 0){
            return $store_main_measurement->sum('main_quantity');
        }else{
            $store_sub_measurement = $this->product->storeProducts()->where([
                ['store_id',$store_id],
                ['sub_measurement_unit_id',$this->measurement_unit_id],
            ])->get();
            if (count($store_sub_measurement) > 0){
                return $store_sub_measurement->sum('sub_quantity_order');
            }else{
                return 0;
            }
        }
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function sellingMethod(){
        return $this->belongsTo(SellingMethod::class,'selling_method_id');
    }

    public function measurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'measurement_unit_id');
    }
}
