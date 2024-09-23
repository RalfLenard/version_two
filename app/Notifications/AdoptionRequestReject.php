<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdoptionRequestReject extends Notification
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
            'reason' => $this->adoptionRequest->reason,
            'message' => 'Sorry your submitted is ' . $this->adoptionRequest->status . ' due to ' . $this->adoptionRequest->reason . '.' ,
        ];
    }
}