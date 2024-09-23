<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnimalAbuseReportSubmitted implements ShouldBroadcast
{
     use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function broadcastOn()
    {
        return new Channel('abuse-reports');
    }

    public function broadcastAs()
    {
        return 'AnimalAbuseReportSubmitted';
    }

    public function broadcastWith()
    {
        return [
            'report' => $this->report,
        ];
    }
}