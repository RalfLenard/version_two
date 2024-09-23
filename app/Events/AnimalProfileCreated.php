<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnimalProfileCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $animal;

    /**
     * Create a new event instance.
     *
     * @param array $animal
     * @return void
     */
    public function __construct($animal)
    {
        $this->animal = $animal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('animal-upload');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastAs()
    {
        return 'animal-profile';
    }
}