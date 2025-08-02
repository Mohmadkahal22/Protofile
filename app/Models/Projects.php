<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Projects extends Model
{
    protected $fillable = [
        'title',
        'project_url',
        'description',
        'video_url',
        'service_id' // أضفنا هذا الحقل للربط

    ];

    public function service()
    {
        return $this->belongsTo(Services::class);
    }
    /**
     * علاقة المشروع بالصور (واحد إلى كثير)
     */
    public function images()
    {
        return $this->hasMany(Imag_Progect::class, 'project_id');
    }

    /**
     * علاقة المشروع بالمميزات (واحد إلى كثير)
     */
    public function features()
    {
        return $this->hasMany(Fetures_Project::class, 'project_id');
    }
}
