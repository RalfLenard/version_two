<?php

namespace App\Events;

use App\Models\AdoptionRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdoptionRequestApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $adoptionRequest;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return void
     */
    public function __construct(AdoptionRequest $adoptionRequest)
    {
        $this->adoptionRequest = $adoptionRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user.' . $this->adoptionRequest->user_id);
    }

    /**
     * Get the data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'adoptionRequestId' => $this->adoptionRequest->id,
            'status' => $this->adoptionRequest->status,
            'message' => 'Your adoption request is approve.',
        ];
    }
}

