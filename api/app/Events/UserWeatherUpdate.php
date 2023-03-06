<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserWeatherUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public array $weather;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user, array $weather)
    {
        $this->user = $user;
        $this->weather = $weather;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('weather'),
            new Channel('weather')
        ];
    }
}