<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Imag_Progect extends Model
{
    protected $table = 'imag__progects'; // Match your migration table name

    protected $fillable = [
        'project_id',
        'image_path',
        'alt_text',
        'order'
    ];

    /**
     * علاقة الصورة بالمشروع (كثير إلى واحد)
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    /**
     * Boot method for automatic file deletion
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            // Delete image file
            if ($image->image_path) {
                $filePath = parse_url($image->image_path, PHP_URL_PATH);
                $filePath = str_replace('api/storage/', '', $filePath);

                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        });
    }
}