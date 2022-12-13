<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class problemTarget extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    //relations

    public function sellerCategory()
    {
        return $this->belongsTo(problemSellerCategory::class,'seller_category_id');
    }

}
