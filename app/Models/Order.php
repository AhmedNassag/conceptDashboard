<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'order_status_id' => 'integer',
        'is_shipping' => 'integer',
        'is_online' => 'integer',
    ];

    public function orderOffer()
    {
        return $this->hasMany(OrderOfferDiscount::class,'order_id');
    }

    public function orderDiscount()
    {
        return $this->hasMany(OrderDiscount::class,'order_id');
    }

    public function orderTax()
    {
        return $this->hasMany(OrderTax::class,'order_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class,'order_id');
    }

    public function orderOtherOffer()
    {
        return $this->hasOne(OrderOtherDiscount::class,'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }

    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }

    public function representative(){
        return $this->belongsTo(User::class,'representative_id');
    }

    public function orderReturn(){
        return $this->hasMany(OrderRetuen::class,'order_id');
    }

    public function clientAccounts(){
        return $this->hasMany(ClientAccount::class,'order_id');
    }

    public function clientIncomes(){
        return $this->hasMany(ClientIncome::class,'order_id');
    }

    public function periodicMaintenance(){
        return $this->hasOne(PeriodicMaintenance::class,'order_id');
    }

}
