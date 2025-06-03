<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryText extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'type',
        'content',
    ];
}
