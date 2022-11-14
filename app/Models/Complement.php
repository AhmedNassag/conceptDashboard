<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function sellingMethod(){
        return $this->belongsTo(SellingMethod::class);
    }
}
