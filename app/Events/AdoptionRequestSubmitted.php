<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdoptionRequestSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $adoptionRequest;

    public function __construct($adoptionRequest)
    {
        $this->adoptionRequest = $adoptionRequest;
    }

    public function broadcastOn()
    {
        return new Channel('adoption-requests');
    }

    public function broadcastAs()
    {
        return 'adoption-request.submitted';
    }

    public function broadcastWith()
    {
        return [
            'adoptionRequest' => $this->adoptionRequest,
        ];
    }
}

