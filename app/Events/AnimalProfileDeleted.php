<?php

namespace App\Events;

use App\Models\AnimalProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AnimalProfileDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $animal;

   public function __construct($animal)
    {
        $this->animal = $animal;
    }

    // Define the broadcast channel
    public function broadcastOn()
    {
        return new Channel('animal-delete');
    }

    // Define the broadcast event name
    public function broadcastAs()
    {
        return 'animal-profile-deleted';
    }

    public function broadcastWith()
    {
        // Only broadcast the ID of the deleted animal
        return ['id' => $this->animal];
    }

}