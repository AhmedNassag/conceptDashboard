<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'integer'
    ];

    //============== appends paths ===========

    protected $appends = [
        'image_path'
    ];

    //append img path

    public function getImagePathAttribute(): string
    {
        $file_name = $this->media->file_name;
        return asset('upload/company/'.$file_name);
    }

    //start raletions

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function earnedDiscounts(){
        return $this->hasMany(EarnedDiscount::class);
    }
}
