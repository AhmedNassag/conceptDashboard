<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'integer',
        'main' => 'integer'
    ];

    // start relation

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function purchases(){

        return $this->hasMany(Purchase::class);
    }

    public function shifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function storeProducts()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    public function supportedAreas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Area::class,'store_areas','store_id','area_id','id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_stores', 'employee_id','store_id', 'id', 'id');
    }

}
