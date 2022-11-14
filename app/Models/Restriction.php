<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = [
        'account_name',
    ];

    public function getAccountNameAttribute()
    {
        return $this->subAccount->name;
    }

    public function dailyRestriction(){
        return $this->belongsTo(DailyRestriction::class,'daily_restriction_id');
    }

    public function subAccount(){
        return $this->belongsTo(SubAccount::class,'sub_account_id');
    }
}
