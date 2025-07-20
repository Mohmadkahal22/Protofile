<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact_Us extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status'
    ];
}
