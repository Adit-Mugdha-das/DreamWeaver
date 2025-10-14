<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DreamArt extends Model
{
    use HasFactory;

    protected $table = 'dream_arts'; // Specify the correct table name

    protected $fillable = [
        'user_id',
        'dream_id',
        'title',
        'prompt',
        'image_path',
        'style',
        'description',
    ];

    /**
     * Get the user that owns the dream art.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the dream associated with this art.
     */
    public function dream()
    {
        return $this->belongsTo(Dream::class);
    }
}
