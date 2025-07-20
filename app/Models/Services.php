<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Services extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path'
    ];

    /**
     * علاقة الخدمة بالمشاريع (واحد إلى كثير)
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Projects::class, 'service_id');
    }


}
