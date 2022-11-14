<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'integer'
    ];

    //relations

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Income::class,'income_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Income::class,'income_id');
    }

    public function incomeAndExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IncomeAndExpense::class);
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
