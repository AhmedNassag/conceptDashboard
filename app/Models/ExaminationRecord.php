<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationRecord extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function storeProducts(){
        return $this->hasMany(StoreProduct::class,'examination_record_id');
    }


}
