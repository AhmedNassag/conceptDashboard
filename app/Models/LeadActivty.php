<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadActivty extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function lead(){
        return $this->belongsTo(Lead::class);
    }

    public function leadFollow(){
        return $this->belongsTo(LeadFollowUp::class);
    }

    public function leadSource(){
        return $this->belongsTo(LeadSource::class);
    }
}
