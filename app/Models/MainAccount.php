<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'debit' => 'integer'
    ];

    protected $appends = [
        'credit_amount',
        'debit_amount',
    ];

    public function getCreditAmountAttribute()
    {
        return $this->subAccounts->sum('credit_transaction');
    }
    public function getDebitAmountAttribute()
    {
        return $this->subAccounts->sum('debit_transaction');
    }

    public function subAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubAccount::class,'main_account_id');
    }
}
