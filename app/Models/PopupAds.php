<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupAds extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //============== appends paths ===========

    protected $appends = [
        'image_path'
    ];

    //append img path

    public function getImagePathAttribute(): string
    {
        $file_name = $this->media->file_name;
        return asset('upload/popupAds/'.$file_name);
    }

    // start Relation

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }
}
