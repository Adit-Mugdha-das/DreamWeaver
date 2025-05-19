<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = ['user_id', 'color', 'item'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
