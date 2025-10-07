<x-app-layout>
    {{-- Mengambil CSS Kustom untuk konsistensi --}}
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f8fafc; }
        .bg-primary { background-color: #2b3cd7; }
        .text-primary { color: #2b3cd7; }
        .bg-accent { background-color: #ffd231; }
        .text-accent { color: #ffd231; }

        /* HEADER STYLING */
        .main-header {
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* CONVERSATION CARD STYLING */
        .conversation-card {
            transition: all 0.3s ease-in-out;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); /* Soft shadow */
        }
        .conversation-card:hover {
            background-color: #f8faff; /* Sangat ringan */
            border-color: #2b3cd7; /* Border Primary */
            transform: translateY(-2px); /* Efek lift yang lebih menonjol */
            box-shadow: 0 6px 10px -2px rgba(0, 0, 0, 0.1);
        }

        /* UNREAD BADGE STYLING (Accent/Kuning) */
        .unread-badge {
            background-color: #ffd231; /* Accent Color (Kuning) */
            color: #2b3cd7; /* Text Primary (Biru Tua) */
            font-weight: 800; /* Extra bold */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 28px; /* Lebih besar */
            min-width: 28px;
            border-radius: 9999px; /* Full rounded */
            box-shadow: 0 0 8px rgba(255, 210, 49, 0.7); /* Soft glow */
            animation: pulse-glow 2s infinite;
        }

        /* Profile Border untuk Unread */
        .border-unread {
            border-color: #ffd231 !important; /* Accent Border */
        }

        /* Animasi Glow untuk badge */
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(255, 210, 49, 0.7); }
            70% { box-shadow: 0 0 0 5px rgba(255, 210, 49, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 210, 49, 0); }
        }
    </style>

    <div class="container mx-auto p-4 md:p-8 max-w-4xl">
        <h1 class="text-3xl font-extrabold mb-8 text-gray-900 main-header">Kotak Masuk Chat 💬</h1>

        <div id="conversation-list" class="space-y-4">
            @forelse($conversations as $conv)
                @php
                    // Menentukan pengguna lawan bicara
                    $otherUser = $conv->customer_id === auth()->id() ? $conv->seller : $conv->customer;

                    // Menghitung pesan belum dibaca
                    $unread = $conv->chats()
                        ->where('sender_id', '!=', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp

                <a href="{{ route('conversations.show', $conv->id) }}"
                   class="conversation-card conversation-link
                          flex items-center justify-between p-5 rounded-xl bg-white"
                   data-conversation-id="{{ $conv->id }}">

                    <div class="flex items-center space-x-4">
                        {{-- Foto Profil Lawan Bicara --}}
                        <img src="{{ $otherUser->profile_photo ? asset('storage/' . $otherUser->profile_photo) : asset('images/profile-user.png') }}"
                             alt="{{ $otherUser->full_name }}"
                             class="w-14 h-14 object-cover rounded-full border-2 {{ $unread > 0 ? 'border-unread' : 'border-gray-300' }}">

                        <div>
                            {{-- Nama Lawan Bicara --}}
                            <p class="font-bold text-xl text-gray-900">{{ $otherUser->full_name }}</p>

                            {{-- Judul Layanan yang Sedang Dibicarakan --}}
                            @if ($conv->service)
                                <p class="text-sm text-gray-600 truncate max-w-xs md:max-w-md">
                                    Membahas: <span class="text-primary font-semibold">{{ Str::limit($conv->service->title, 40) }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Badge Pesan Belum Dibaca --}}
                    @if($unread > 0)
                        <span class="unread-count unread-badge text-sm leading-none"
                              style="width: fit-content;">
                            {{ $unread > 99 ? '99+' : $unread }}
                        </span>
                    @endif
                </a>

            @empty
                <div class="text-center py-10 bg-white rounded-xl border border-gray-200 shadow-md">
                    <p class="text-gray-500 font-medium">Anda belum memiliki percakapan aktif. 😴</p>
                    <p class="text-gray-500 mt-2 text-sm">Cari layanan dan mulai chat dengan penjual untuk memulai!</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Logika JavaScript (Pusher) Tetap Sama --}}
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const authIdMeta = document.querySelector('meta[name="auth-id"]');
            if (!authIdMeta) {
                console.error("Meta tag 'auth-id' tidak ditemukan. Realtime chat mungkin tidak berfungsi.");
                return;
            }

            const authId = Number(authIdMeta.content);

            const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                forceTLS: true,
            });

            document.querySelectorAll(".conversation-link").forEach((link) => {
                const conversationId = link.dataset.conversationId;

                const channel = pusher.subscribe("conversation." + conversationId);

                channel.bind("ChatSent", (data) => {
                    // Hanya perbarui jika pengirim BUKAN pengguna saat ini
                    if (data.chat.sender_id !== authId) {
                        const linkElement = document.querySelector(`.conversation-link[data-conversation-id="${conversationId}"]`);
                        if (!linkElement) return;

                        let span = linkElement.querySelector(".unread-count");
                        const img = linkElement.querySelector('img');

                        if (!span) {
                            // Buat badge baru jika belum ada
                            span = document.createElement("span");
                            span.classList.add("unread-count", "unread-badge", "text-sm", "leading-none");
                            span.style.width = 'fit-content';
                            linkElement.appendChild(span);
                        }

                        // Tambahkan border Accent ke foto profil
                        if (img) {
                            img.classList.add('border-unread');
                            img.classList.remove('border-gray-300');
                        }

                        // Parse dan tingkatkan hitungan
                        let count = parseInt(span.textContent.replace(/\D/g, "") || "0");
                        span.textContent = `${count + 1}`;

                        // Opsional: Pindahkan card ke atas daftar untuk memprioritaskan chat baru
                        const list = document.getElementById('conversation-list');
                        if (list && linkElement !== list.firstElementChild) {
                            list.prepend(linkElement);
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
