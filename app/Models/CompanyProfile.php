<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'company_profiles';

    //============== appends paths ===========
    protected $appends = ['image_path'];

    //append img path
    public function getImagePathAttribute(): string
    {
        $file_name = $this->media->file_name;
        return asset('upload/companyProfile/' . $file_name);
    }

    //start relations
    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
