<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    protected $casts = [
        'is_account' => 'integer',
    ];

    protected $appends = [
        'total_price',
        'sender_name',
    ];

    public function getSenderNameAttribute()
    {
        if ($this->supplier_id != null){
            return $this->supplier->name_supplier;
        }else{
            return $this->client->name;
        }
    }


    public function getTotalPriceAttribute(){
        $main_quantity = $this->returnProducts->sum(function ($item){
            return $item->price * $item->quantity;
        });

        $sub_quantity = $this->returnProducts->sum(function ($item){
            return $item->sub_price * $item->sub_quantity;
        });

        return $main_quantity + $sub_quantity;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }

    public function returnProducts(){
        return $this->hasMany(ReturnProduct::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function client(){
        return $this->belongsTo(User::class,'client_id','id');
    }

    public function supplierIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierIncome::class);
    }

    public function clientIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientIncome::class);
    }

    public function supplierAccount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierAccount::class);
    }

    public function clientAccount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientAccount::class);
    }
}
