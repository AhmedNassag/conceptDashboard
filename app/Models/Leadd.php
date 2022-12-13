<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function leadClient(){
        return $this->hasOne(LeadActivty::class);
    }

}
