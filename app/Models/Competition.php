<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'integer'
    ];

    //============== appends paths ===========
    protected $appends = ['image_path'];

    //append img path
    public function getImagePathAttribute(): string
    {
        if($this->media)
        {
            $file_name = $this->media->file_name;
            return asset('upload/competition/' . $file_name);

        }
        return false;
    }

    //start raletions
    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
