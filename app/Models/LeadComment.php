<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadComment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
}
