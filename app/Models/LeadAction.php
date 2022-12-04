<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadAction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function lead(){
        return $this->belongsTo(Lead::class);
    }
}
