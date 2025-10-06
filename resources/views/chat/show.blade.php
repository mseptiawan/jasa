<x-app-layout>
    <meta name="csrf-token"
          content="{{ csrf_token() }}" />
    <meta name="auth-id"
          content="{{ auth()->id() }}" />
    <meta name="conversation-id"
          content="{{ $conversation->id }}" />

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f4f8;
        }

        .bg-primary {
            background-color: #2b3cd7;
        }

        .text-primary {
            color: #2b3cd7;
        }

        .chat-bubble {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            line-height: 1.4;
            transition: all 0.2s;
        }

        /* Scrollbar styling */
        #messages::-webkit-scrollbar {
            width: 8px;
        }

        #messages::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }

        /* Responsivitas bubble */
        .chat-bubble-width-mine,
        .chat-bubble-width-other {
            max-width: 90%;
        }

        @media (min-width: 640px) {

            .chat-bubble-width-mine,
            .chat-bubble-width-other {
                max-width: 75%;
            }
        }

        /* Template Buttons */
        .template-btn {
            white-space: nowrap;
        }
    </style>

    <div class="container mx-auto p-0 md:p-8 max-w-4xl">


        {{-- Header Chat --}}
        <div
             class="bg-white p-4 rounded-t-lg shadow-md flex items-center justify-between border-b border-gray-200 sticky top-0 z-10">
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                <a href="#"
                   onclick="history.back(); return false;"
                   class="text-gray-500 hover:text-primary transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                Chat dengan
                <span class="text-primary truncate">
                    @if (auth()->id() === $conversation->customer_id)
                        {{ $conversation->seller->full_name }}
                    @else
                        {{ $conversation->customer->full_name }}
                    @endif
                </span>
            </h1>

            @if ($conversation->service)
                <a href="{{ route('services.show', $conversation->service->slug) }}"
                   class="text-sm text-gray-600 hover:text-primary transition-colors hover:underline hidden sm:block">
                    Membahas: <span class="font-medium">{{ Str::limit($conversation->service->title, 15) }}</span>
                </a>
            @endif
        </div>

        {{-- Kotak Pesan --}}
        <div id="messages"
             class="p-4 h-[60vh] min-h-72 overflow-y-auto bg-white border-x border-b border-gray-200 shadow-inner">

            @php $lastDate = null; @endphp
            @foreach ($chats as $chat)
                @php
                    $chatDate = $chat->created_at->format('Y-m-d');
                    $isSender = $chat->sender_id == auth()->id();
                    $bubbleClass = $isSender
                        ? 'bg-primary text-white ml-auto rounded-tr-none'
                        : 'bg-gray-100 text-gray-800 mr-auto rounded-tl-none';
                    $alignmentClass = $isSender ? 'justify-end' : 'justify-start';

                    $showDateSeparator = $lastDate !== $chatDate;
                    $lastDate = $chatDate;

                    $timeDisplay = $chat->created_at->format('H:i');
                @endphp

                {{-- Pemisah Tanggal --}}
                @if ($showDateSeparator)
                    <div class="flex justify-center my-4">
                        <span class="text-xs text-gray-500 px-3 py-1 bg-gray-200 rounded-full shadow-sm">
                            {{ $chat->created_at->translatedFormat('d F Y') }}
                        </span>
                    </div>
                @endif

                <div data-chat-id="{{ $chat->id }}"
                     class="flex mb-3 {{ $alignmentClass }}">
                    <div class="chat-bubble-width-mine chat-bubble-width-other">

                        @if (isset($chat->is_product) && $chat->is_product)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden flex gap-3 p-3">
                                <img src="{{ $chat->product_image ?? 'https://via.placeholder.com/80' }}"
                                     alt="Produk"
                                     class="w-20 h-20 object-cover rounded-lg border" />

                                <div class="flex flex-col justify-between">
                                    <a href="{{ $chat->product_link ?? '#' }}"
                                       class="font-semibold text-primary hover:underline">
                                        {{ $chat->product_name ?? 'Nama Produk' }}
                                    </a>
                                    <p class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($chat->product_price ?? 0, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $chat->message }}
                                    </p>
                                    <a href="{{ $chat->product_link ?? '#' }}"
                                       class="mt-2 inline-block text-xs font-medium text-white bg-primary px-3 py-1 rounded-md hover:bg-opacity-90 transition">
                                        Lihat Produk
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="chat-bubble inline-block px-4 py-3 rounded-xl {{ $bubbleClass }} break-words">
                                {!! $chat->message !!}
                            </div>
                        @endif

                        <div class="text-gray-400 text-xs mt-1 {{ $isSender ? 'text-right' : 'text-left' }}">
                            {{ $timeDisplay }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Form Input Pesan --}}
        <form id="chatForm"
              action="{{ route('conversations.send', $conversation->id) }}"
              class="p-4 bg-white rounded-b-lg shadow-lg border-t border-gray-200">
            @csrf
            @if (session('success'))
                <div class="bg-green-500 text-white p-2 rounded mb-2">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-2">
                    {{ session('error') }}
                </div>
            @endif
            {{-- Template Text Buttons --}}
            {{-- Template Text Buttons --}}
            @if (auth()->id() === $conversation->customer_id)
                <div class="mb-4 flex flex-wrap gap-2">
                    <button type="button"
                            class="template-btn text-xs font-medium text-gray-700 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-gray-200 transition">
                        Halo, apakah jasa ini masih tersedia?
                    </button>
                    <button type="button"
                            class="template-btn text-xs font-medium text-gray-700 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-gray-200 transition">
                        Bisakah saya mendapatkan penawaran khusus?
                    </button>
                    <button type="button"
                            class="template-btn text-xs font-medium text-gray-700 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-gray-200 transition">
                        Berapa lama waktu pengerjaannya?
                    </button>
                </div>
            @endif

            <div class="flex gap-3">
                <input type="text"
                       name="message"
                       id="chat-input"
                       class="flex-1 border border-gray-300 px-4 py-3 rounded-md focus:ring-1 focus:ring-primary focus:border-primary transition duration-150"
                       placeholder="Tulis pesan..."
                       autofocus />
                <button type="submit"
                        class="bg-primary text-white font-semibold px-6 py-3 rounded-md hover:bg-opacity-90 transition duration-150 flex items-center gap-1"
                        id="sendButton">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 rotate-90"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim
                </button>
            </div>
        </form>
    </div>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const authId = Number(document.querySelector('meta[name="auth-id"]').content);
            const conversationId = Number(document.querySelector('meta[name="conversation-id"]').content);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const messagesContainer = document.querySelector("#messages");
            const chatForm = document.querySelector("#chatForm");
            const sendButton = document.querySelector("#sendButton");
            const chatInput = document.getElementById('chat-input');
            const templateButtons = document.querySelectorAll('.template-btn');

            const scrollToBottom = () => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            };

            const getTimeDisplay = (date) => {
                return date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                forceTLS: true
            });

            const channel = pusher.subscribe(`conversation.${conversationId}`);

            channel.bind("ChatSent", (data) => {
                if (!messagesContainer.querySelector(`[data-chat-id='${data.chat.id}']`)) {
                    const isSender = data.chat.sender_id === authId;
                    const bubbleClass = isSender ? 'bg-primary text-white ml-auto rounded-tr-none' :
                        'bg-gray-100 text-gray-800 mr-auto rounded-tl-none';
                    const alignmentClass = isSender ? 'justify-end' : 'justify-start';
                    const now = new Date();
                    const timeDisplay = getTimeDisplay(now);

                    // default: normal bubble
                    let chatHtml = `
                        <div data-chat-id="${data.chat.id}" class="flex mb-3 ${alignmentClass}">
                            <div class="chat-bubble-width-mine chat-bubble-width-other">
                                <div class="chat-bubble inline-block px-4 py-3 rounded-xl ${bubbleClass} break-words">
                                    ${data.chat.message}
                                </div>
                                <div class="text-gray-400 text-xs mt-1 ${isSender ? 'text-right' : 'text-left'}">
                                    ${timeDisplay}
                                </div>
                            </div>
                        </div>
                    `;

                    if (data.chat.is_product) {
                        chatHtml = `
                            <div data-chat-id="${data.chat.id}" class="flex mb-3 ${alignmentClass}">
                                <div class="chat-bubble-width-mine chat-bubble-width-other">
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden flex gap-3 p-3">
                                        <img src="${data.chat.product_image ?? 'https://via.placeholder.com/80'}"
                                             alt="Produk"
                                             class="w-20 h-20 object-cover rounded-lg border" />
                                        <div class="flex flex-col justify-between">
                                            <a href="${data.chat.product_link ?? '#'}"
                                               class="font-semibold text-primary hover:underline">
                                               ${data.chat.product_name ?? 'Nama Produk'}
                                            </a>
                                            <p class="text-lg font-bold text-green-600">
                                                Rp ${data.chat.product_price ?? 0}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                ${data.chat.message}
                                            </p>
                                            <a href="${data.chat.product_link ?? '#'}"
                                               class="mt-2 inline-block text-xs font-medium text-white bg-primary px-3 py-1 rounded-md hover:bg-opacity-90 transition">
                                               Lihat Produk
                                            </a>
                                        </div>
                                    </div>
                                    <div class="text-gray-400 text-xs mt-1 ${isSender ? 'text-right' : 'text-left'}">
                                        ${timeDisplay}
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    messagesContainer.insertAdjacentHTML('beforeend', chatHtml);
                    scrollToBottom();
                }
            });

            // Handle form submission
            chatForm.addEventListener("submit", (e) => {
                e.preventDefault();
                const input = chatInput;
                const originalButtonText = sendButton.innerHTML;
                if (!input.value.trim()) return;

                sendButton.disabled = true;
                sendButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Mengirim...
                `;

                fetch(e.target.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json",
                        },
                        body: new FormData(e.target),
                    })
                    .then(async res => {
                        if (!res.ok) {
                            const data = await res.json();
                            throw data; // biar masuk ke .catch
                        }
                        return res.json();
                    })
                    .then(data => {
                        input.value = "";
                        scrollToBottom();
                    })
                    .catch(err => {
                        if (err.error) {
                            toastr.warning(err.error);
                        } else {
                            toastr.error('Gagal mengirim pesan.');
                        }

                    })
                    .finally(() => {
                        sendButton.disabled = false;
                        sendButton.innerHTML = originalButtonText;
                    });

            });

            templateButtons.forEach(button => {
                button.addEventListener('click', () => {
                    chatInput.value = button.textContent.trim();
                    chatInput.focus();
                });
            });

            scrollToBottom();
        });
    </script>
</x-app-layout>
