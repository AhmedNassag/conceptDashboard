<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function sellerCategory()
    {
        return $this->belongsTo(SellerCategory::class,'seller_category_id');
    }

    public function comments()
    {
        return $this->hasMany(LeadComment::class,'lead_id');
    }
}
