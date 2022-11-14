<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // relations

    public function suggestionUser(){
        return $this->hasMany(SuggestionUser::class,'suggestion_id');
    }
}
