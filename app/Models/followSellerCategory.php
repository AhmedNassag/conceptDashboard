<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class followSellerCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $translatedAttributes = ['name'];

    protected $translationForeignKey = 'seller_category_id';

    protected $hidden = ['follow_category_translations'];

    public function employees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Employee::class,'follow_employee_categories','seller_category_id','employee_id','id','id');
    }

    public function targets(){
        $this->hasMany(followTarget::class,'seller_category_id');
    }

    public function leads(){
        $this->hasMany(followLead::class,'seller_category_id');
    }

    public function targetAchieved()
    {
        return $this->hasMany(followTargetAchieved::class);
    }
}
