<?php

namespace App\Events;

use App\Models\AnimalProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnimalAdopted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $animalProfile;

    public function __construct(AnimalProfile $animalProfile)
    {
        $this->animalProfile = $animalProfile;
    }

    public function broadcastOn()
    {
        return new Channel('animal-adopted');
    }

    public function broadcastAs()
    {
        return 'animal.adopted';
    }

    public function broadcastWith()
    {
        return [
            'animalProfile' => $this->animalProfile,
        ];
    }
}