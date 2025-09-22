<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // Channel notifikasi, bisa 'mail', 'database', dll.
    public function via($notifiable)
    {
        return ['database']; // kita pakai notifikasi database
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'service_name' => $this->order->service->name,
            'customer_name' => $this->order->customer->name,
            'message' => 'Pesanan baru dibuat untuk jasa kamu!',
        ];
    }

    // Kalau mau pakai email juga, bisa tambah toMail()
}
