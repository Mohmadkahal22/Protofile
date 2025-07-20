<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Imag_Progect extends Model
{
    protected $fillable = [
        'project_id',
        'image_path',
    ];

    /**
     * علاقة الصورة بالمشروع (كثير إلى واحد)
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Projects::class);
    }


}
