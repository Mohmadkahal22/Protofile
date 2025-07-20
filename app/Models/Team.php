<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Team extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'photo',
        'github_url',
        'cv_file',
        'specialization',
        'position'
    ];


}
