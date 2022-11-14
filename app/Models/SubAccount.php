<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'count_elements'
    ];

    public function getCountElementsAttribute()
    {
        return  $this->children()->count() ;
    }

    public function getDebitTransactionAttribute()
    {
        return  $this->restriction()->where('debit',1)->get()->sum('amount') ;
    }

    public function getCreditTransactionAttribute()
    {
        return  $this->restriction()->where('debit',0)->get()->sum('amount') ;
    }

    //relations

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubAccount::class,'sub_account_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubAccount::class,'sub_account_id');
    }

    public function mainAccount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MainAccount::class,'main_account_id');
    }

    public function restriction(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Restriction::class,'sub_account_id');
    }

    public function restrictionRecord(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RestrictionRecord::class);
    }
}
