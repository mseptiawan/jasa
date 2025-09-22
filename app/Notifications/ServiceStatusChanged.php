<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ServiceStatusChanged extends Notification
{
    use Queueable;

    protected $service;
    protected $status;

    public function __construct($service, $status)
    {
        $this->service = $service;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database']; // bisa ditambah 'mail' kalau mau email
    }

    public function toDatabase($notifiable)
    {
        return [
            'service_id' => $this->service->id,
            'service_title' => $this->service->title,
            'status' => $this->status,
            'message' => "Layanan jasa '{$this->service->title}' Anda telah {$this->status} oleh admin."
        ];
    }
}
