<x-app-layout>
    <div id="conversation-list">
        @foreach($conversations as $conv)
        @php
        $otherUser = $conv->customer_id === auth()->id() ? $conv->seller : $conv->customer;
        $unread = $conv->chats()
        ->where('sender_id', '!=', auth()->id())
        ->where('is_read', false)
        ->count();
        @endphp

        <a href="{{ route('conversations.show', $conv->id) }}"
           class="conversation-link"
           data-conversation-id="{{ $conv->id }}"
           style="
                display: flex;
                justify-content: space-between;
                padding: 8px 12px;
                margin-bottom: 6px;
                border: 1px solid #ccc;
                border-radius: 6px;
                text-decoration: none;
                color: #000;
                background: #f9f9f9;
           ">
            <span>{{ $otherUser->full_name }}</span>
            @if($unread > 0)
            <span class="unread-count"
                  style="
                        background: red;
                        color: #fff;
                        border-radius: 50%;
                        padding: 2px 6px;
                        font-size: 12px;
                      ">
                {{ $unread }}
            </span>
            @endif
        </a>
        @endforeach
    </div>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        const authId = Number(
            document.querySelector('meta[name="auth-id"]').content
        );

        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: false,
        });

        document.querySelectorAll(".conversation-link").forEach((link) => {
            const conversationId = link.dataset.conversationId;

            // Subscribe ke private channel
            const channel = pusher.subscribe("conversation." + conversationId);

            // Bind ke nama event yang sama dengan broadcastAs()
            channel.bind("ChatSent", (data) => {
                if (data.chat.sender_id !== authId) {
                    let span = link.querySelector(".unread-count");
                    if (!span) {
                        span = document.createElement("span");
                        span.classList.add("unread-count");
                        link.appendChild(span);
                    }
                    let count = parseInt(
                        span.textContent.replace(/\D/g, "") || "0"
                    );
                    span.textContent = ` (${count + 1})`;
                }
            });
        });
    </script>
</x-app-layout>
