<?php
namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy {
    public function view(User $user, Conversation $conv): bool {
        return $conv->participants()->where('user_id', $user->id)->exists();
    }
    public function send(User $user, Conversation $conv): bool {
        return $this->view($user, $conv);
    }
}