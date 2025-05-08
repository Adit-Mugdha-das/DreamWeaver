<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dream extends Model
{
    protected $fillable = ['title', 'content', 'emotion_summary', 'interpretation', 'story'];
}
