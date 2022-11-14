<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestrictionRecord extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'debit' => 'integer',
    ];

    //relations

    protected $appends = [
        'user_name',
        'account_name',
    ];

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }
    public function getAccountNameAttribute()
    {
        return $this->subAccount->name;;
    }

    public function dailyRestriction(){
        return $this->belongsTo(DailyRestriction::class,'daily_restriction_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function subAccount(){
        return $this->belongsTo(SubAccount::class,'sub_account_id');
    }
}
