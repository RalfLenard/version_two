<?php

namespace App\Events;

use App\Models\Meeting;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateMeetingScheduled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $meeting;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Meeting $meeting
     * @return void
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user.' . $this->meeting->user_id); // Ensure user_id is correct
    }

    /**
     * Get the data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'meeting_id' => $this->meeting->id,
            'meeting_date' => $this->meeting->meeting_date,
            'status' => $this->meeting->status,
            'message' => 'Your meeting has been updated to ' . $this->meeting->meeting_date . '.',
        ];
    }
}
