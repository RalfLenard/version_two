<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AdoptionRequestVerifying extends Notification
{
    use Queueable;

    protected $adoptionRequest;

    public function __construct($adoptionRequest)
    {
        $this->adoptionRequest = $adoptionRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // Store notification in the database
    }

    public function toArray($notifiable)
    {
        return [
            'adoptionRequestId' => $this->adoptionRequest->id,
            'status' => $this->adoptionRequest->status,
            'message' => 'Your adoption request is now under ' . $this->adoptionRequest->status . '.',
        ];
    }
}
