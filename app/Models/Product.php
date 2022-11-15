<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id'];

    protected $appends = ['text','image_path'];

    protected $casts = [
        'status' => 'integer',
        'sell_app' => 'integer'
    ];

    public function getTextAttribute(): string
    {
        return $this->name ;
    }

    //append img path

    public function getImagePathAttribute(): string
    {
        $file_name = $this->image;
        return asset('upload/product/'.$file_name);
    }

    // start relation

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function subCategory(){
        return $this->belongsTo(Category::class,'sub_category_id');
    }

    public function mainMeasurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'main_measurement_unit_id');
    }

    public function subMeasurementUnit(){
        return $this->belongsTo(MeasurementUnit::class,'sub_measurement_unit_id');
    }

    public function tax(){
        return $this->belongsTo(Tax::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class,'mediable');
    }

    public function  selling_method()
    {
        return $this->belongsToMany(SellingMethod::class,'product_selling_methods','product_id','selling_method_id');
    }

    public function purchaseProducts(){

        return $this->hasMany(PurchaseProduct::class);
    }

    public function storeProducts(){
        return $this->hasMany(StoreProduct::class,'product_id');
    }


    public function returnProducts(){
        return $this->hasMany(ReturnProduct::class,'product_id');
    }

    public function productPrice(){
        return $this->hasMany(ProductPricing::class,'product_id');
    }

    public function pricingHistories(){
        return $this->hasMany(PricingHistory::class);
    }

    public function popupAds(){
        return $this->hasOne(PopupAds::class);
    }

    public function orderProduct(){
        return $this->hasMany(OrderStoreProduct::class);
    }

    public function orderDetails(){
        return $this->hasMany(OrderDetails::class);
    }

    public function orderRetuen(){
        return $this->hasMany(OrderRetuen::class);
    }

    public function sparePart(){
        return $this->belongsToMany(SparePart::class,'spare_part_products');
    }

    public function filterWax(){
        return $this->belongsToMany(FilterWax::class,'filter_wax_products');
    }

    public function maintenance(){
        return $this->hasOne(Maintenance::class);
    }

}
