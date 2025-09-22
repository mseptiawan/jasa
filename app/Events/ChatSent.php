<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        // Private channel per conversation
        return new Channel('conversation.' . $this->chat->conversation_id);
    }

    public function broadcastAs()
    {
        return 'ChatSent';
    }

    public function broadcastWith()
    {
        return [
            'chat' => [
                'id' => $this->chat->id,
                'message' => $this->chat->message,
                'sender_id' => $this->chat->sender_id,
            ],
        ];
    }
}
