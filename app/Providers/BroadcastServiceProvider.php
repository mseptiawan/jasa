<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Kalau mau public channel, ga perlu callback auth
        Broadcast::routes();

        // Contoh channel public, optional
        Broadcast::channel('conversation.{conversationId}', function ($conversationId) {
            return true; // semua orang bisa subscribe
        });
    }
}
