<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function sparePart(){
        return $this->belongsToMany(Product::class,'spare_part_products');
    }

}
