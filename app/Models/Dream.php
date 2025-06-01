<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dream extends Model
{
    protected $fillable = [
        'title',
        'content',
        'emotion_summary',
        'short_interpretation',
        'story_generation',   // ✅ Corrected
        'long_narrative',     // ✅ Missing before — now added
        'user_id'
    ];
}
