<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class About_Us extends Model
{
    protected $fillable = [
        'company_name',
        'company_description',
        'website_url',
        'foundation_date',
        'contact_email',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'github_url',
        'company_logo'
    ];


}
