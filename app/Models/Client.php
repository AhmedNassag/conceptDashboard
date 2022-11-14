<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'integer'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function province(){
        return $this->belongsTo(Province::class,'province_id');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id');
    }

    public function sellingMethod(){
        return $this->belongsTo(SellingMethod::class,'selling_method_id');
    }
}
