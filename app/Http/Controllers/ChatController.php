<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Conversation;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Events\ChatSent;
use Carbon\Carbon;

class ChatController extends Controller
{
    // List semua conversation user
    public function index()
    {
        $conversations = Conversation::where('customer_id', auth()->id())
            ->orWhere('seller_id', auth()->id())
            ->with(['customer', 'seller', 'product'])
            ->get();

        return view('chat.index', compact('conversations'));
    }

    // Tampilkan chat untuk satu conversation
    public function show(Conversation $conversation)
    {
        // tandai semua chat lawan sebagai read
        $conversation->chats()
            ->where('sender_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        $chats = $conversation->chats()->with('sender')->orderBy('created_at')->get();
        return view('chat.show', compact('conversation', 'chats'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $request->validate(['message' => 'required|string']);
        $userId = auth()->id();

        // Hitung jumlah pesan dalam 1 menit terakhir
        $recentMessages = Chat::where('sender_id', $userId)
            ->where('conversation_id', $conversation->id)
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        if ($recentMessages >= 3) {
            // Balas JSON kalau user lewat batas
            return response()->json([
                'error' => 'Kamu dibatasi: maksimal 3 pesan per menit. Coba lagi nanti.'
            ], 429);
        }

        $chat = Chat::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $request->message,
        ]);

        broadcast(new ChatSent($chat))->toOthers();

        return response()->json([
            'chat' => [
                'id' => $chat->id,
                'message' => e($chat->message),
                'sender_id' => $chat->sender_id,
            ],
            'success' => true
        ]);
    }


    // Mulai percakapan baru
    public function start(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'product_id' => 'nullable|exists:services,id'
        ]);

        // Ambil atau buat conversation
        $conversation = Conversation::firstOrCreate([
            'customer_id' => auth()->id(),
            'seller_id' => $request->seller_id,
            'product_id' => $request->product_id
        ]);

        // Jika ada product_id dan chat pertama belum ada
        if ($request->product_id && $conversation->chats()->count() == 0) {
            $product = Service::find($request->product_id);
            $thumbnail = $product->images ? asset('storage/' . json_decode($product->images)[0]) : null;

            // Tentukan teks role
            $roleText = auth()->user()->role === 'customer'
                ? "Anda bertanya tentang produk ini:"
                : "Customer bertanya tentang produk ini:";

            $message = "<div class='flex items-center gap-2 border p-2 rounded bg-gray-700'>
                " . ($thumbnail ? "<img src='{$thumbnail}' class='w-16 h-16 object-cover rounded'>" : "") . "
                <div>
                    <a href='" . route('services.show', $product->id) . "' class='text-white font-semibold underline'>
                        {$product->title}
                    </a><br>
                    <span class='text-white'>Rp " . number_format($product->price, 0, ',', '.') . "</span><br>
                    <span class='text-gray-300'>{$roleText}</span>
                </div>
            </div>";

            Chat::create([
                'conversation_id' => $conversation->id,
                'sender_id' => auth()->id(),
                'message' => $message
            ]);
        }

        return redirect()->route('conversations.show', $conversation->id);
    }
}
