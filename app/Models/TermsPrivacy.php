<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsPrivacy extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'terms_privacies';
}
