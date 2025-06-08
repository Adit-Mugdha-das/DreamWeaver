<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Dream;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DreamCommented extends Notification
{
    use Queueable;

    protected $commenter;
    protected $dream;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $commenter, Dream $dream)
    {
        $this->commenter = $commenter;
        $this->dream = $dream;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database']; // Optionally add 'mail' if needed
    }

    /**
     * Get the array representation of the notification (for database).
     */
    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'comment',
            'message' => "{$this->commenter->name} commented on your dream: {$this->dream->title}",
            'dream_id' => $this->dream->id,
            'user_id' => $this->commenter->id,
        ];
    }
}
