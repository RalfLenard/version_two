<?php

namespace App\Events;

use App\Models\AnimalProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AnimalProfileUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $animal;

    public function __construct(AnimalProfile $animal)
    {
        $this->animal = $animal;
    }

    // Define the broadcast channel
    public function broadcastOn()
    {
        return new Channel('animal-updates');
    }

    // Define the broadcast event name
    public function broadcastAs()
    {
        return 'animal-profile-updated';
    }
}
