<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fetures_Project extends Model
{
    protected $fillable = [
        'project_id',
        'feature_text',

    ];

    /**
     * علاقة الميزة بالمشروع (كثير إلى واحد)
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Projects::class);
    }
}
