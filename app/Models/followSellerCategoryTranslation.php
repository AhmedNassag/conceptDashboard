<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class followSellerCategoryTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $table = 'follow_category_translations';
}
