<?php
// app/Notifications/MessageReceived.php
namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MessageReceived extends Notification
{
    use Queueable;
    public function __construct(public Message $message) {}
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable): array {
        return [
            'type' => 'chat',
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'from_user_id' => $this->message->user_id,
            'body_preview' => str($this->message->body)->limit(120),
        ];
    }
}
