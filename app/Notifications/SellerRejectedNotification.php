<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        // kirim ke email dan database
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Seller Ditolak')
            ->line('Pengajuan akun seller kamu ditolak.')
            ->line($this->reason ? 'Alasan: ' . $this->reason : '')
            ->line('Silakan ajukan kembali setelah memperbaiki data.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Pengajuan Seller Ditolak',
            'message' => $this->reason
                ? 'Pengajuan seller kamu ditolak. Alasan: ' . $this->reason
                : 'Pengajuan seller kamu ditolak.',
            'url'     => route('dashboard'),
        ];
    }
}
