<x-app-layout>
    <meta name="csrf-token"
          content="{{ csrf_token() }}" />
    <meta name="auth-id"
          content="{{ auth()->id() }}" />
    <meta name="conversation-id"
          content="{{ $conversation->id }}" />

    <style>
        /* BASE & THEME */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc; /* Lighter background */
        }

        .bg-primary {
            background-color: #2b3cd7;
        }

        .text-primary {
            color: #2b3cd7;
        }

        .bg-accent {
            background-color: #ffd231;
        }

        .text-accent {
            color: #ffd231;
        }

        /* CHAT BUBBLE STYLES */
        .chat-bubble {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); /* Shadow yang lebih halus */
            line-height: 1.4;
            transition: all 0.2s;
            max-width: 100%; /* Dikelola oleh container parent */
        }

        /* Bubble Saya: Primary dengan sedikit gradasi */
        .chat-bubble.mine {
            background: linear-gradient(135deg, #2b3cd7, #4f63e7); /* Primary + sedikit terang */
            color: white;
            border-radius: 12px 12px 0 12px; /* Lebih modern */
        }

        /* Bubble Lain: Light Grey/Soft */
        .chat-bubble.other {
            background-color: #eef2ff; /* indigo-50 */
            color: #1f2937;
            border-radius: 12px 12px 12px 0; /* Lebih modern */
        }

        /* SCROLLBAR & LAYOUT */
        #messages-container-wrapper {
             position: relative; /* Wadah untuk elemen melayang */
             border-radius: 0 0 12px 12px;
        }

        #messages {
            height: 60vh;
            min-height: 72;
            overflow-y: auto;
            background-color: white;
            padding: 1rem;
        }
        #messages::-webkit-scrollbar {
            width: 8px;
        }

        #messages::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }

        /* RESPONSIVITY */
        .chat-bubble-width-container {
            max-width: 90%;
        }

        @media (min-width: 640px) {
            .chat-bubble-width-container {
                max-width: 70%; /* Lebih sempit di desktop */
            }
        }

        /* CUSTOM WARNINGS & ALERTS */
        .system-warning-bubble {
            background-color: #fff8e6; /* Sangat ringan kekuningan/accent */
            color: #2b3cd7; /* Text Primary */
            border: 1px solid #ffd231; /* Accent Border */
            border-radius: 0.5rem;
            padding: 0.75rem;
            text-align: center;
        }

        /* Custom style untuk limit warning (Total Chat Limit - Merah) */
        .chat-limit-warning {
            background-color: #ffe7e7; /* Merah muda */
            color: #ef4444; /* Merah asli */
            font-weight: 600;
            border: 1px solid #fecaca;
        }

        /* START: Custom CSS for Rate Limit Toast/Alert (Warning Style) */
        #rate-limit-toast {
            position: fixed;
            top: 1.5rem;
            left: 50%;
            transform: translateX(-50%) translateY(-100%);
            z-index: 1000;
            max-width: 90%;
            width: 400px;
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: #fffdf5;
            border: 2px solid #ffd231; /* Border Accent untuk Warning */
            color: #2b3cd7; /* Text Primary */
            font-weight: 600;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        #rate-limit-toast.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        /* Styling untuk GIF Alarm */
        .rate-limit-gif {
            width: 2rem;
            height: 2rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        /* END: Custom CSS for Rate Limit Toast/Alert */

        /* SEARCH Bar Styling (Floating/Melayang) */
        #search-container-float {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50; /* Di atas pesan */
            padding: 1rem;
            background-color: rgba(255, 255, 255, 0.95); /* Sedikit transparan */
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);

            /* Transisi Sembunyi/Tampil */
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden; /* Tambahkan visibility */
            transition: transform 0.3s ease-out, opacity 0.3s ease-out, visibility 0.3s;
        }
        #search-container-float.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        .highlight {
            background-color: #ffd231; /* Accent color for highlight */
            padding: 2px;
            border-radius: 4px;
        }
    </style>

    {{-- START: Rate Limit Warning Toast (Diletakkan di atas) --}}
    <div id="rate-limit-toast" role="alert">
        <img src="{{ asset('images/alarm.gif') }}" alt="Alarm" class="rate-limit-gif" />

        <span id="rate-limit-message" class="text-primary font-bold">
            Kamu dibatasi: maksimal 3 pesan per menit. Coba lagi nanti.
        </span>
    </div>
    {{-- END: Rate Limit Warning Toast --}}

    <div class="container mx-auto p-0 md:p-8 max-w-4xl">

        @php
            $CHAT_LIMIT = 50;
            $currentChatCount = $chats->count();
            $isChatLimitReached = $currentChatCount >= $CHAT_LIMIT && $conversation->status === 'active';

            $isNewConversation = $currentChatCount === 0;

            $receiverName = auth()->id() === $conversation->customer_id ? $conversation->seller->full_name : $conversation->customer->full_name;
        @endphp


        {{-- Header Chat (Tidak Berubah Strukturnya) --}}
        <div
             class="bg-white p-4 rounded-t-xl shadow-md flex flex-col border-b border-gray-200 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                {{-- Breadcrumb/Back Button and Title --}}
                <h1 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <a href="#"
                       onclick="history.back(); return false;"
                       class="text-gray-500 hover:text-primary transition">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewbox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    Chat dengan
                    <span class="text-primary truncate font-extrabold">
                        @if (auth()->id() === $conversation->customer_id)
                            {{ $conversation->seller->full_name }}
                        @else
                            {{ $conversation->customer->full_name }}
                        @endif
                    </span>
                </h1>

                {{-- Service Link and Search Button --}}
                <div class="flex items-center gap-4">
                    @if ($conversation->service)
                        <a href="{{ route('services.show', $conversation->service->slug) }}"
                           class="text-sm text-gray-600 hover:text-primary transition-colors hover:underline hidden sm:block">
                            Membahas: <span class="font-medium">{{ Str::limit($conversation->service->title, 15) }}</span>
                        </a>
                    @endif

                    {{-- Tombol Search (Ditingkatkan) --}}
                    <button type="button" id="toggle-search"
                            class="text-gray-500 hover:text-primary transition p-2 rounded-full hover:bg-gray-100 flex-shrink-0 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Catatan: Search bar dipindahkan ke bawah --}}

        </div>

        {{-- Kotak Pesan Container (Dibungkus agar search bar bisa melayang) --}}
        <div id="messages-container-wrapper" class="border-x border-b border-gray-200">

            {{-- Floating Search Bar (Melayang di atas pesan) --}}
            <div id="search-container-float">
                <input type="text"
                       id="search-input"
                       placeholder="Cari pesan..."
                       class="w-full border border-gray-300 px-4 py-2 rounded-xl focus:ring-1 focus:ring-primary focus:border-transparent transition duration-150" />
            </div>

            <div id="messages"
                 class="shadow-inner">

                {{-- START: PESAN PERINGATAN AWAL CHAT (Safety Warning) --}}
                @if ($isNewConversation)
                    <div class="flex justify-center my-6">
                        <div class="system-warning-bubble chat-bubble-width-container text-sm">
                            <p class="font-bold mb-1 flex items-center justify-center gap-2 text-primary">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-accent">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Peringatan Keamanan!
                            </p>
                            <p class="text-gray-700">Demi keamanan transaksi, **jangan pernah** membagikan informasi sensitif seperti nomor rekening bank, kata sandi, atau detail kartu kredit/debit kepada {{ $receiverName }}. Semua transaksi harus melalui platform resmi.</p>
                        </div>
                    </div>
                    <div class="flex justify-center mb-6">
                        <span class="text-xs text-gray-500 px-3 py-1 bg-gray-200 rounded-full shadow-sm">
                            Mulai Chat Baru
                        </span>
                    </div>
                @endif
                {{-- END: PESAN PERINGATAN AWAL CHAT --}}


                @php $lastDate = null; @endphp
                @foreach ($chats as $chat)
                    @php
                        $chatDate = $chat->created_at->format('Y-m-d');
                        $isSender = $chat->sender_id == auth()->id();
                        $bubbleClass = $isSender ? 'mine' : 'other'; // Menggunakan kelas CSS baru
                        $alignmentClass = $isSender ? 'justify-end' : 'justify-start';

                        $showDateSeparator = $lastDate !== $chatDate;
                        $lastDate = $chatDate;

                        $timeDisplay = $chat->created_at->format('H:i');
                    @endphp

                    {{-- Pemisah Tanggal --}}
                    @if ($showDateSeparator && !$isNewConversation)
                        <div class="flex justify-center my-4">
                            <span class="text-xs text-gray-500 px-3 py-1 bg-gray-200 rounded-full shadow-sm">
                                {{ $chat->created_at->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    @endif

                    <div data-chat-id="{{ $chat->id }}"
                         class="flex mb-3 {{ $alignmentClass }} chat-message-item"> {{-- Tambahkan kelas item untuk pencarian --}}
                        {{-- Container baru untuk kontrol max-width --}}
                        <div class="chat-bubble-width-container">

                            @if (isset($chat->is_product) && $chat->is_product)
                                {{-- Product Bubble --}}
                                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden flex gap-3 p-3">
                                    <img src="{{ $chat->product_image ?? 'https://via.placeholder.com/80' }}"
                                         alt="Produk"
                                         class="w-20 h-20 object-cover rounded-lg border" />

                                    <div class="flex flex-col justify-between">
                                        <a href="{{ route('services.show', $conversation->service->slug) }}"
                                           class="font-semibold text-primary hover:underline hover:text-primary/80 transition-colors">
                                            {{ $chat->product_name ?? 'Nama Produk' }}
                                        </a>
                                        <p class="text-lg font-bold text-green-600">
                                            Rp {{ number_format($chat->product_price ?? 0, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm text-gray-600 chat-text-content">
                                            {{ $chat->message }}
                                        </p>
                                        <a href="{{ route('services.show', $conversation->service->slug) }}"
                                           class="mt-2 inline-block text-xs font-medium text-white bg-primary px-3 py-1 rounded-md hover:bg-opacity-90 transition">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{-- Regular Bubble --}}
                                <div class="chat-bubble inline-block px-4 py-3 {{ $bubbleClass }} break-words chat-text-content"> {{-- Tambahkan kelas konten --}}
                                    {!! $chat->message !!}
                                </div>
                            @endif

                            <div class="text-gray-400 text-xs mt-1 {{ $isSender ? 'text-right' : 'text-left' }}">
                                {{ $timeDisplay }}
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- START: TAMPILAN JIKA CHAT LIMIT TERCAPAI (Total Pesan) --}}
                @if ($isChatLimitReached)
                    <div class="flex justify-center my-6">
                        <div class="chat-limit-warning p-4 rounded-lg text-center max-w-sm">
                            <p class="font-bold mb-1">Batas Percakapan Tercapai</p>
                            <p class="text-sm">Anda telah mencapai batas maksimal {{ $CHAT_LIMIT }} pesan untuk percakapan ini. Untuk melanjutkan, silakan buat Pesanan atau hubungi layanan pelanggan.</p>
                        </div>
                    </div>
                @endif
                {{-- END: TAMPILAN JIKA CHAT LIMIT TERCAPAI (Total Pesan) --}}

            </div>

        </div>

        {{-- Form Input Pesan --}}
        <form id="chatForm"
              action="{{ route('conversations.send', $conversation->id) }}"
              class="p-4 bg-white border-t border-gray-200">
            @csrf

            {{-- Non-aktifkan input jika limit TERCAPAI --}}
            @if ($isChatLimitReached)
                <div class="p-3 chat-limit-warning rounded-md text-center mb-4">
                    Pesan tidak dapat dikirim. Batas chat telah tercapai.
                </div>
            @endif

            {{-- Template Text Buttons --}}
            @if (auth()->id() === $conversation->customer_id && !$isChatLimitReached)
                <div class="mb-4 flex flex-wrap gap-2">
                    <button type="button"
                            class="template-btn text-xs font-medium text-gray-700 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-primary hover:text-white transition">
                        Halo, apakah jasa ini masih tersedia?
                    </button>
                    <button type="button"
                            class="template-btn text-xs font-medium text-gray-700 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-primary hover:text-white transition">
                        Bisakah saya mendapatkan penawaran khusus?
                    </button>
                    <button type="button"
                            class="template-btn text-xs font-medium text-gray-700 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-primary hover:text-white transition">
                        Berapa lama waktu pengerjaannya?
                    </button>
                </div>
            @endif

            <div class="flex gap-3">
                <input type="text"
                       name="message"
                       id="chat-input"
                       class="flex-1 border border-gray-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition duration-150"
                       placeholder="Tulis pesan..."
                       autofocus
                       {{ $isChatLimitReached ? 'disabled' : '' }} />
                <button type="submit"
                        class="bg-primary text-white font-semibold px-6 py-3 rounded-xl hover:bg-opacity-90 transition duration-150 flex items-center gap-1 shadow-md {{ $isChatLimitReached ? 'opacity-50 cursor-not-allowed' : '' }}"
                        id="sendButton"
                        {{ $isChatLimitReached ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 rotate-90"
                         fill="none"
                         viewbox="0 0 24 24"
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
            const rateLimitToast = document.getElementById('rate-limit-toast');
            const rateLimitMessage = document.getElementById('rate-limit-message');

            // Search Elements
            const toggleSearchButton = document.getElementById('toggle-search');
            const searchContainerFloat = document.getElementById('search-container-float');
            const searchInput = document.getElementById('search-input');
            const chatMessageItems = document.querySelectorAll('.chat-message-item');

            const isChatLimitReached = {{ $isChatLimitReached ? 'true' : 'false' }};
            let searchTimeout;


            // --- FUNCTIONS ---

            // Fungsi untuk menampilkan Rate Limit Toast
            const showRateLimitToast = (message) => {
                rateLimitMessage.textContent = message;
                rateLimitToast.classList.add('show');
                setTimeout(() => {
                    rateLimitToast.classList.remove('show');
                }, 5000);
            };

            const scrollToBottom = () => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            };

            const getTimeDisplay = (date) => {
                return date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            // Fungsi Pencarian
            const performSearch = () => {
                const query = searchInput.value.toLowerCase().trim();
                let firstMatchFound = false;

                // Sembunyikan semua pesan saat pencarian dimulai (akan ditampilkan jika cocok)
                chatMessageItems.forEach(item => {
                    item.style.display = 'none';
                    const contentElement = item.querySelector('.chat-text-content');
                    if (contentElement) contentElement.innerHTML = contentElement.textContent || contentElement.innerText; // Reset highlight
                });

                if (query === '') {
                     chatMessageItems.forEach(item => { item.style.display = 'flex'; }); // Tampilkan semua jika query kosong
                     return;
                }

                chatMessageItems.forEach(item => {
                    const contentElement = item.querySelector('.chat-text-content');
                    if (!contentElement) return;

                    const plainText = contentElement.textContent || contentElement.innerText;
                    const textContentLower = plainText.toLowerCase();

                    if (textContentLower.includes(query)) {
                        const regex = new RegExp('(' + query.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&') + ')', 'gi');
                        contentElement.innerHTML = plainText.replace(regex, '<span class="highlight">$&</span>');

                        item.style.display = 'flex';

                        if (!firstMatchFound) {
                            item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstMatchFound = true;
                        }
                    }
                });
            };


            // --- EVENT LISTENERS ---

            // 1. Pusher Logic (Dipertahankan)
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                forceTLS: true
            });

            const channel = pusher.subscribe(`conversation.${conversationId}`);

            channel.bind("ChatSent", (data) => {
                if (!messagesContainer.querySelector(`[data-chat-id='${data.chat.id}']`)) {
                    const isSender = data.chat.sender_id === authId;
                    const bubbleClass = isSender ? 'mine' : 'other';
                    const alignmentClass = isSender ? 'justify-end' : 'justify-start';
                    const now = new Date();
                    const timeDisplay = getTimeDisplay(now);

                    let chatHtml = `
                        <div data-chat-id="${data.chat.id}" class="flex mb-3 ${alignmentClass} chat-message-item">
                            <div class="chat-bubble-width-container">
                                ${data.chat.is_product ? `
                                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden flex gap-3 p-3">
                                        <img src="${data.chat.product_image ?? 'https://via.placeholder.com/80'}"
                                             alt="Produk"
                                             class="w-20 h-20 object-cover rounded-lg border" />
                                        <div class="flex flex-col justify-between">
                                            <a href="${data.chat.product_link ?? '#'}"
                                               class="font-semibold text-primary hover:underline hover:text-primary/80 transition-colors">
                                               ${data.chat.product_name ?? 'Nama Produk'}
                                            </a>
                                            <p class="text-lg font-bold text-green-600">
                                                Rp ${new Intl.NumberFormat('id-ID').format(data.chat.product_price ?? 0)}
                                            </p>
                                            <p class="text-sm text-gray-600 chat-text-content">
                                                ${data.chat.message}
                                            </p>
                                            <a href="${data.chat.product_link ?? '#'}"
                                               class="mt-2 inline-block text-xs font-medium text-white bg-primary px-3 py-1 rounded-md hover:bg-opacity-90 transition">
                                                Lihat Produk
                                            </a>
                                        </div>
                                    </div>
                                ` : `
                                    <div class="chat-bubble inline-block px-4 py-3 ${bubbleClass} break-words chat-text-content">
                                        ${data.chat.message}
                                    </div>
                                `}
                                <div class="text-gray-400 text-xs mt-1 ${isSender ? 'text-right' : 'text-left'}">
                                    ${timeDisplay}
                                </div>
                            </div>
                        </div>
                    `;

                    messagesContainer.insertAdjacentHTML('beforeend', chatHtml);
                    scrollToBottom();
                }
            });

            // 2. Chat Form Submission (Dipertahankan)
            chatForm.addEventListener("submit", (e) => {
                e.preventDefault();
                const input = chatInput;
                const originalButtonText = sendButton.innerHTML;

                if (!input.value.trim() || isChatLimitReached) return;

                sendButton.disabled = true;
                sendButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24">
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

                            if (res.status === 429 && data.message) {
                                showRateLimitToast(data.message);
                            } else if (data.error) {
                                showRateLimitToast(data.error);
                            } else {
                                showRateLimitToast('Gagal mengirim pesan. Silakan coba lagi.');
                            }

                            throw data;
                        }
                        return res.json();
                    })
                    .then(data => {
                        input.value = "";
                        scrollToBottom();
                    })
                    .catch(err => {
                        console.error("Gagal mengirim pesan:", err);
                    })
                    .finally(() => {
                        sendButton.disabled = false;
                        sendButton.innerHTML = originalButtonText;
                    });
            });

            // 3. Template Buttons (Dipertahankan)
            templateButtons.forEach(button => {
                button.addEventListener('click', () => {
                    if (!isChatLimitReached) {
                        chatInput.value = button.textContent.trim();
                        chatInput.focus();
                    }
                });
            });

            // 4. Search Feature Logic Fix
            toggleSearchButton.addEventListener('click', () => {
                const isShowing = searchContainerFloat.classList.toggle('show');

                if (isShowing) {
                    searchInput.focus();
                    if (searchInput.value.trim() !== '') {
                        performSearch();
                    }
                } else {
                    // Reset tampilan chat saat search bar ditutup
                    searchInput.value = '';
                    performSearch(); // Menghapus highlight dan menampilkan semua pesan
                }
            });

            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 300);
            });


            scrollToBottom();
        });
    </script>
</x-app-layout>
