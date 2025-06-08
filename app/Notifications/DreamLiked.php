<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Dream;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DreamLiked extends Notification
{
    use Queueable;

    protected $liker;
    protected $dream;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $liker, Dream $dream)
    {
        $this->liker = $liker;
        $this->dream = $dream;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // You can also add 'mail' if needed
    }

    /**
     * Get the array representation of the notification (used for database).
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'like',
            'message' => "{$this->liker->name} liked your dream: {$this->dream->title}",
            'dream_id' => $this->dream->id,
            'user_id' => $this->liker->id,
        ];
    }
}
