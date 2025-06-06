<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dream extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'emotion_summary',
        'short_interpretation',
        'story_generation',
        'long_narrative',
        'user_id',
        'is_shared' // ✅ Added field to support public sharing
    ];

    /**
     * Get the user who owns the dream.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
