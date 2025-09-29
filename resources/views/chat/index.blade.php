<x-app-layout>
    {{-- Mengambil CSS Kustom untuk konsistensi --}}
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f8fafc; }
        .bg-primary { background-color: #2b3cd7; }
        .text-primary { color: #2b3cd7; }
        .bg-accent { background-color: #ffd231; }

        /* Styling spesifik untuk link percakapan */
        .conversation-card {
            transition: all 0.2s ease-in-out;
        }
        .conversation-card:hover {
            background-color: #f2f4ff; /* Light blue/indigo hover */
            border-color: #2b3cd7; /* Border Primary */
            transform: translateY(-1px);
        }
        .unread-badge {
            background-color: #ef4444; /* Tailwind red-500 */
            color: #fff;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 24px;
            min-width: 24px;
        }
    </style>

    <div class="container mx-auto p-4 md:p-8 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-gray-900">Kotak Masuk Chat 💬</h1>

        <div id="conversation-list" class="space-y-3">
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
                          flex items-center justify-between p-4 rounded-xl border border-gray-200
                          bg-white shadow-sm hover:shadow-md transition-all duration-200"
                   data-conversation-id="{{ $conv->id }}">

                    <div class="flex items-center space-x-4">
                        {{-- Foto Profil Lawan Bicara --}}
                        <img src="{{ $otherUser->profile_photo ? asset('storage/' . $otherUser->profile_photo) : asset('images/profile-user.png') }}"
                             alt="{{ $otherUser->full_name }}"
                             class="w-12 h-12 object-cover rounded-full border-2 {{ $unread > 0 ? 'border-red-500' : 'border-gray-300' }}">

                        <div>
                            {{-- Nama Lawan Bicara --}}
                            <p class="font-semibold text-lg text-gray-900">{{ $otherUser->full_name }}</p>

                            {{-- Judul Layanan yang Sedang Dibicarakan (Opsional, jika tersedia) --}}
                            @if ($conv->service)
                                <p class="text-sm text-gray-500 truncate max-w-xs">
                                    Membahas: <span class="text-primary font-medium">{{ $conv->service->title }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Badge Pesan Belum Dibaca --}}
                    @if($unread > 0)
                        <span class="unread-count unread-badge text-xs rounded-full p-1 leading-none"
                              style="width: fit-content;">
                            {{ $unread }}
                        </span>
                    @endif
                </a>

            @empty
                <div class="text-center py-10 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-gray-500">Anda belum memiliki percakapan aktif.</p>
                    <p class="text-gray-500 mt-2">Cari layanan dan mulai chat dengan penjual!</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Logika JavaScript (Pusher) Tetap Sama --}}
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Pastikan meta tag auth-id tersedia
            const authIdMeta = document.querySelector('meta[name="auth-id"]');
            if (!authIdMeta) {
                console.error("Meta tag 'auth-id' tidak ditemukan. Realtime chat mungkin tidak berfungsi.");
                return;
            }

            const authId = Number(authIdMeta.content);

            const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                forceTLS: true, // Sebaiknya gunakan TLS: true untuk produksi
            });

            document.querySelectorAll(".conversation-link").forEach((link) => {
                const conversationId = link.dataset.conversationId;

                // Subscribe ke channel private
                // Pastikan channel bernama 'private-conversation.<id>' jika menggunakan otentikasi
                const channel = pusher.subscribe("conversation." + conversationId);

                // Bind ke event ChatSent
                channel.bind("ChatSent", (data) => {
                    // Hanya perbarui jika pengirim BUKAN pengguna saat ini
                    if (data.chat.sender_id !== authId) {
                        const linkElement = document.querySelector(`.conversation-link[data-conversation-id="${conversationId}"]`);
                        if (!linkElement) return;

                        let span = linkElement.querySelector(".unread-count");
                        if (!span) {
                            // Buat badge baru jika belum ada
                            span = document.createElement("span");
                            span.classList.add("unread-count", "unread-badge", "text-xs", "rounded-full", "p-1", "leading-none");
                            span.style.width = 'fit-content';
                            linkElement.appendChild(span);

                            // Tambahkan border merah ke foto profil
                            const img = linkElement.querySelector('img');
                            if (img) {
                                img.classList.add('border-red-500');
                                img.classList.remove('border-gray-300');
                            }
                        }

                        // Parse dan tingkatkan hitungan
                        let count = parseInt(span.textContent.replace(/\D/g, "") || "0");
                        span.textContent = `${count + 1}`;
                    }
                });
            });
        });
    </script>
</x-app-layout>
