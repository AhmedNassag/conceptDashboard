<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpareAccessor extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    //append img path

    public function getImagePathAttribute(): string
    {
        $file_name = $this->media->file_name;
        return asset('upload/spareAccessor/' . $file_name);
    }

    //start raletions
    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function spareAccessor()
    {
        return $this->belongsToMany(Product::class, 'spare_accessor_products');
    }

}
