<?php

namespace App\Events;

use Auth;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender;
    public $receiver;
    public $auth_user;
    public $groupId;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $sender, $receiver= null, $groupId = null)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->auth_user = Auth::user();
        $this->groupId = $groupId;
    }

    public function broadcastOn()
    {
        if ($this->groupId) {
            return new Channel('group-chat' . $this->groupId);
        }

        return [
            new PrivateChannel('chat' . $this->receiver->id),
            new PrivateChannel('chat' . $this->sender->id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'sender' => $this->sender,
            'receiver' => $this->receiver ? $this->receiver : null,
            'group_id' => $this->groupId,
            'isGroupChat' => $this->groupId !== null,
        ];
    }

}
