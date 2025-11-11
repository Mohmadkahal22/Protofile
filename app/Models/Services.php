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




        protected static function boot()
    {
        parent::boot();

        // Automatically delete files when model is deleted
        static::deleting(function ($service) {
            // Delete image file
            if ($service->image_path) {
                $filePath = parse_url($service->image_path, PHP_URL_PATH);
                $filePath = str_replace('api/storage/', '', $filePath);

                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        });
    }

    /**
     * Accessor for projects count
     */
    public function getProjectsCountAttribute(): int
    {
        return $this->projects()->count();
    }

}
