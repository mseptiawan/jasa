<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Conversation;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Events\ChatSent;

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

    // Kirim pesan baru
    public function send(Request $request, Conversation $conversation)
    {
        $request->validate(['message' => 'required|string']);

        $chat = Chat::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        broadcast(new ChatSent($chat))->toOthers();


        return response()->json([
            'id' => $chat->id,
            'message' => $chat->message,
            'sender_id' => $chat->sender_id,
        ]);
    }

    // Start conversation baru
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
            if (auth()->user()->role === 'customer') {
                $roleText = "Anda bertanya tentang produk ini:";
            } else {
                $roleText = "Customer bertanya tentang produk ini:";
            }

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
                'sender_id' => auth()->id(), // bisa disesuaikan, misal system/buyer
                'message' => $message
            ]);
        }

        return redirect()->route('conversations.show', $conversation->id);
    }
}
