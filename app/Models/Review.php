<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',          // اسم الشخص (إذا لم يكن مسجلًا)
        'content',       // نص التقييم
        'rating',        // درجة التقييم (من 1 إلى 5)
        'review_date',   // تاريخ التقييم
        'is_approved'    // إذا كان التقييم معتمدًا
    ];
}
