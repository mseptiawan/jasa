<x-app-layout>
    <meta name="csrf-token"
          content="{{ csrf_token() }}" />
    <meta name="auth-id"
          content="{{ auth()->id() }}" />
    <meta name="conversation-id"
          content="{{ $conversation->id }}" />

    <div class="container mx-auto py-6 max-w-3xl">
        <h1 class="text-2xl font-bold mb-4">Chat dengan
            @if (auth()->id() === $conversation->customer_id)
                {{ $conversation->seller->full_name }}
            @else
                {{ $conversation->customer->full_name }}
            @endif
        </h1>

        <div id="messages"
             class="border p-4 h-96 overflow-y-auto mb-4 bg-gray-50 rounded">
            @foreach ($chats as $chat)
                <div data-chat-id="{{ $chat->id }}"
                     class="mb-2 {{ $chat->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                    <div
                         class="{{ $chat->sender_id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black' }} inline-block px-3 py-2 rounded-lg max-w-[70%] break-words">
                        {!! $chat->message !!}
                    </div>
                    <div class="text-gray-400 text-xs mt-1">
                        {{ $chat->created_at->format('d-m-Y H:i') }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Form input pesan -->
        <form id="chatForm"
              action="{{ route('conversations.send', $conversation->id) }}">
            @csrf
            <div class="flex gap-2">
                <input type="text"
                       name="message"
                       class="flex-1 border px-3 py-2 rounded"
                       placeholder="Tulis pesan..." />
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded">Kirim</button>
            </div>
        </form>
    </div>

    <!-- Pusher -->
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        const authId = Number(document.querySelector('meta[name="auth-id"]').content);
        const conversationId = Number(document.querySelector('meta[name="conversation-id"]').content);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const messages = document.querySelector("#messages");

        // Initialize 
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: false
        });

        // Subscribe ke channel conversation
        const channel = pusher.subscribe("conversation." + conversationId);

        // bind event
        channel.bind("ChatSent", (data) => {
            if (!messages.querySelector(`[data-chat-id='${data.chat.id}']`)) {
                const div = document.createElement("div");
                div.setAttribute("data-chat-id", data.chat.id);
                div.classList.add("mb-2", data.chat.sender_id == authId ? "text-right" : "text-left");

                div.innerHTML = `<div class="${data.chat.sender_id == authId ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black'} inline-block px-3 py-2 rounded-lg max-w-[70%] break-words">
                                    ${data.chat.message}
                                </div>
                                <div class="text-gray-400 text-xs mt-1">${new Date().toLocaleString()}</div>`;
                messages.appendChild(div);
                messages.scrollTop = messages.scrollHeight;
            }
        });

        // Form submit AJAX
        document.querySelector("#chatForm").addEventListener("submit", (e) => {
            e.preventDefault();
            const input = e.target.querySelector('input[name="message"]');
            if (!input.value.trim()) return;

            fetch(e.target.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                    body: new FormData(e.target),
                })
                .then(() => input.value = "")
                .catch(err => console.error(err));
        });

        // Scroll otomatis ke bawah saat load
        messages.scrollTop = messages.scrollHeight;
    </script>
</x-app-layout>
