<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //============== appends paths ===========

    protected $appends = [
        'image_path',
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    //append img path

    public function getImagePathAttribute(): string
    {
        $file_name = $this->media->file_name;
        return asset('upload/category/'.$file_name);
    }

    //start raletions

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }

    public function parent()
    {
        return $this->belongsTo(self::class,'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class,'parent_id');
    }

    public function products(){
        return $this->hasMany(Product::class,'category_id');
    }

    public function subCategoryProducts(){
        return $this->hasMany(Product::class,'sub_category_id');
    }

}
