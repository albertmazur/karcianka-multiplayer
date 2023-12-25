<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private User $user;
    private string $data;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $data){
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array{
        return [
            new PrivateChannel('PrivateGameChannel.user.1'),
        ];
    }

    public function broadcastAs(){
        return 'private_game';
    }

    public function broadcastWith(){
        return [
            'data' => $this->data
        ];
    }
}
