<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerRejectedNotification extends Notification
{
    use Queueable;

    protected $reason;

    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Seller Ditolak')
            ->view('emails.seller-rejected', [
                'user' => $notifiable,
                'reason' => $this->reason,
                'url' => route('dashboard'),
            ]);
    }


    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Pengajuan Seller Ditolak',
            'message' => $this->reason
                ? 'Pengajuan kamu ditolak. Alasan: ' . $this->reason
                : 'Pengajuan kamu ditolak.',
            'url'     => route('dashboard'),
        ];
    }
}
