<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRestriction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = [
        'debits',
        'creditor',
        'amount'
    ];

    public function getAmountAttribute()
    {
        return $this->restriction->where('debit',0)->sum('amount');
    }

    public function getDebitsAttribute()
    {
        return $this->restriction->where('debit',1)->sum('amount');
    }

    public function getCreditorAttribute()
    {
        return $this->restriction->where('debit',0)->sum('amount');
    }

    public function restriction(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Restriction::class,'daily_restriction_id');
    }

    public function restrictionRecord(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RestrictionRecord::class,'daily_restriction_id');
    }
}
