<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = [
        'quantity',
        'sender_name',
        'product_price',
    ];

    protected $casts = [
        'is_received' => 'integer',
    ];

    public function getPriceAttribute($value)
    {
        if (count($this->supplierExpense) > 0){
            return  $this->supplierExpense()->sum('amount') ;
        }elseif (count($this->clientExpense) > 0){
            return  $this->clientExpense()->sum('amount') ;
        }
        return  $value ;
    }

    public function getQuantityAttribute()
    {
        return  $this->purchaseProducts()->sum('quantity') + $this->purchaseProducts()->sum('sub_quantity') ;
    }
    public function getSenderNameAttribute()
    {
        if ($this->supplier_id != null){
            return $this->supplier->name_supplier;
        }else{
            return $this->user->name;
        }
    }

    public function getProductPriceAttribute(){
        $product_price = 0;

        foreach ($this->purchaseProducts as $product){
            $product_price +=  $product['quantity'] * $product['price'];
            $product_price +=  $product['sub_quantity'] * ($product['price'] / $product['count_unit']) ;
        }

        return $product_price;
    }

    public function purchaseProducts(){

        return $this->hasMany(PurchaseProduct::class);
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function examinationRecord(){

        return $this->hasOne(ExaminationRecord::class,'purchase_id');

    }

    public function purchaseReturns (){
        return $this->hasOne(PurchaseReturn::class,'purchase_id');
    }

    public function supplierAccounts(){
        return $this->hasMany(SupplierAccount::class);
    }

    public function clientAccount(){
        return $this->hasMany(ClientAccount::class);
    }

    public function supplierExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierExpense::class);
    }

    public function clientExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class);
    }

}
