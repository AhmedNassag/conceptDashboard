<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class followTarget extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    //relations

    public function sellerCategory()
    {
        return $this->belongsTo(followSellerCategory::class,'seller_category_id');
    }

}
