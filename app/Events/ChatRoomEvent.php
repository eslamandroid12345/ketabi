<?php

namespace App\Events;

use App\Http\Resources\V1\Chat\ChatRoomEventResource;
use App\Http\Resources\V1\Chat\ChatRoomResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatRoomEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($data, $user_id)
    {
        $this->user_id = $user_id;
        $this->data = new ChatRoomEventResource($data, $user_id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.rooms.'.$this->user_id),
        ];
    }
}
