<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model {
    protected $fillable = ['topic'];
    public function participants(): BelongsToMany {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function messages(): HasMany {
        return $this->hasMany(Message::class);
    }
    public function otherParticipant(?int $myId) {
        return $this->participants()->where('user_id', '!=', $myId)->first();
    }
}