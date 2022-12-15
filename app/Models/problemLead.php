<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class problemLead extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function sellerCategory()
    {
        return $this->belongsTo(problemSellerCategory::class,'seller_category_id');
    }

    public function comments()
    {
        return $this->hasMany(problemLeadComment::class,'lead_id');
    }
}
