<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = [
        'amount',
        'income',
        'income_transfer',
        'expense',
        'expense_transfer',
    ];

    //---------------------------- total amount in treasury

    public function getAmountAttribute()
    {
        return ( $this->income + $this->income_transfer ) - ( $this->expense + $this->expense_transfer );
    }

    //--------------------------- income in treasury

    public function getIncomeAttribute()
    {
        $totalIncome = 0 ;
        $totalIncome += $this->incomeAndExpense->whereNotNull('income_id')->sum('amount');
        $totalIncome += $this->capitalOwnerAccount->whereNotNull('income_id')->sum('amount');
        $totalIncome += $this->supplierIncome->sum('amount');
        $totalIncome += $this->clientIncome->sum('amount');
        return $totalIncome;
    }

    public function getIncomeTransferAttribute()
    {
        return $this->toTransferringTreasury->sum('amount');
    }

    // ------------------- expense in treasury
    public function getExpenseAttribute()
    {
        $totalExpense = 0;
        $totalExpense += $this->incomeAndExpense->whereNotNull('expense_id')->sum('amount');
        $totalExpense += $this->capitalOwnerAccount->whereNotNull('expense_id')->sum('amount');
        $totalExpense += $this->supplierExpense->sum('amount');
        $totalExpense += $this->clientExpense->sum('amount');
        return $totalExpense;
    }

    public function getExpenseTransferAttribute()
    {
        return $this->fromTransferringTreasury->sum('amount');
    }

    //--------------------- relations

    public function treasuryParent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Treasury::class, 'treasury_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Treasury::class, 'treasury_id');
    }

    public function incomeAndExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IncomeAndExpense::class);
    }

    public function fromTransferringTreasury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransferringTreasury::class, 'from_treasury_id');
    }

    public function toTransferringTreasury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransferringTreasury::class, 'to_treasury_id');
    }

    public function supplierExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierExpense::class);
    }
    public function clientExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class);
    }
    public function supplierIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierIncome::class);
    }
    public function clientIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientIncome::class);
    }

    public function capitalOwnerAccount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CapitalOwnerAccount::class);
    }
}
