<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    //start relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class, 'competition_id');
    }


}
