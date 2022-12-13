<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class problemTargetAchieved extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = [
        'commission'
    ];
    public function getCommissionAttribute()
    {
        if ($this->seller_category_id == 1 || $this->seller_category_id == 2 || $this->seller_category_id == 3 || $this->seller_category_id == 4)
        {
            $target = problemTarget::where([
                ['seller_category_id',$this->seller_category_id],
                ['from','<=',$this->count],
                ['to','>=',$this->count],
            ])->first();
            if (!$target){
                $target = problemTarget::where([
                    ['seller_category_id',$this->seller_category_id],
                ])->get()->last();
            }
            return $target->amount;
        }else{
            $target = problemTarget::where([
                ['seller_category_id',$this->seller_category_id],
                ['from','<=',$this->count],
                ['to','>=',$this->count],
            ])->first();
            if (!$target){
                $target = problemTarget::where([
                    ['seller_category_id',$this->seller_category_id],
                ])->get()->last();
            }
            return $target->percent;
        }

    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function sellerCategory()
    {
        return $this->belongsTo(problemSellerCategory::class,'seller_category_id');
    }
}
