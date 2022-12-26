<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }

    public function shifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'employee_stores', 'employee_id','store_id', 'id', 'id');
    }


    // CRM relations
    public function sellerCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SellerCategory::class,'employee_categories','employee_id','seller_category_id','id','id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class,'employee_id');
    }

    public function comments()
    {
        return $this->hasMany(LeadComment::class);
    }

    public function targetAchieved()
    {
        return $this->hasMany(TargetAchieved::class);
    }


    //problem relations
    public function problemsellerCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(problemSellerCategory::class,'problem_employee_categories','employee_id','seller_category_id','id','id');
    }

    public function problemleads()
    {
        return $this->hasMany(problemLead::class,'employee_id');
    }

    public function problemcomments()
    {
        return $this->hasMany(problemLeadComment::class);
    }

    public function problemtargetAchieved()
    {
        return $this->hasMany(problemTargetAchieved::class);
    }


    //follow relations
    public function followsellerCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(followSellerCategory::class,'follow_employee_categories','employee_id','seller_category_id','id','id');
    }

    public function followleads()
    {
        return $this->hasMany(followLead::class,'employee_id');
    }

    public function followcomments()
    {
        return $this->hasMany(followLeadComment::class);
    }

    public function followtargetAchieved()
    {
        return $this->hasMany(followTargetAchieved::class);
    }
}
