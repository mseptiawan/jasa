<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerApprovedNotification extends Notification
{
    use Queueable;

    public function __construct() {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Seller Disetujui')
            ->view('emails.seller-approved', [
                'user' => $notifiable,
                'url' => route('dashboard'),
            ]);
    }


    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Pendaftaran Seller Disetujui',
            'message' => 'Selamat, pengajuan kamu untuk menjadi seller telah disetujui!',
            'url'     => route('dashboard'),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
