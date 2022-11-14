<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'integer'
    ];

    //relations

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Expense::class,'expense_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Expense::class,'expense_id');
    }

    public function incomeAndExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IncomeAndExpense::class);
    }

    public function supplierExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierExpense::class);
    }

    public function clientExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class);
    }

    public function capitalOwnerAccount(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CapitalOwnerAccount::class);
    }
}
