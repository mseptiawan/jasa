<x-app-layout>
    {{-- CSS Kustom (Mengambil Style Konsisten dari Dashboard & Menambah Toast) --}}
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc; /* Diambil dari dashboard.blade.php */
        }

        .bg-primary {
            background-color: #2b3cd7; /* Diambil dari dashboard.blade.php */
        }

        .text-primary {
            color: #2b3cd7; /* Diambil dari dashboard.blade.php */
        }

        .border-primary {
            border-color: #2b3cd7; /* Diambil dari dashboard.blade.php */
        }

        .bg-accent {
            background-color: #ffd231; /* Diambil dari dashboard.blade.blade.php */
        }

        .text-accent {
            color: #ffd231; /* Diambil dari dashboard.blade.php */
        }

        .text-green-600 {
            color: #16a34a;
        }

        .text-yellow-400 {
            color: #fbbf24;
        }

        .text-red-500 {
            color: #ef4444; /* Diambil dari dashboard.blade.php */
        }

        /* Custom Scrollbar for reviews */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Styling tambahan untuk gambar agar terlihat lebih rapi */
        .image-display {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
            border-radius: 0.75rem;
            /* rounded-xl */
        }

        /* Pastikan SVG yang di-inject bisa diwarnai dan diukur */
        .social-icon-wrapper svg {
            width: 1rem; /* setara dengan w-4 */
            height: 1rem; /* setara dengan h-4 */
            color: currentColor;
        }

        /* Card Style untuk Jasa Lainnya (Diambil dari dashboard.blade.php - Tanpa Shadow) */
        .normal-service-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            border-radius: 0.5rem; /* rounded-lg */
            transition: transform 0.3s ease-in-out;
            /* TANPA SHADOW */
        }

        .normal-service-card:hover {
            transform: translateY(-4px); /* hover:-translate-y-1 */
        }

        /* START: Custom CSS for Toast Notification (Success Style) */
        #toast-notification {
            position: fixed;
            bottom: 1.5rem; /* md:right-4 */
            right: 1.5rem; /* md:bottom-4 */
            z-index: 100;
            max-width: 320px;
            /* Flex box di Toast */
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem; /* py-3 px-4 */
            border-radius: 0.375rem; /* rounded-md */
            background-color: #fff;
            border: 1px solid #d1d5db; /* border-gray-300 */
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); /* shadow-lg */
            /* Animasi masuk/keluar */
            opacity: 0;
            visibility: hidden;
            transform: translateY(100%);
            transition: opacity 0.3s ease-out, transform 0.3s ease-out, visibility 0.3s;
        }

        #toast-notification.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .toast-icon {
            width: 1.25rem; /* w-5 */
            height: 1.25rem; /* h-5 */
            color: #10b981; /* Tailwind green-500 for Success */
            flex-shrink: 0;
            margin-right: 0.5rem; /* mr-2 */
        }

        .toast-text-container {
            flex-grow: 1;
        }

        .toast-title {
            font-weight: 600; /* font-semibold */
            color: #10b981;
            font-size: 0.875rem; /* text-sm */
        }

        .toast-message {
            color: #4b5563; /* text-gray-600 */
            font-size: 0.875rem; /* text-sm */
        }
        /* END: Custom CSS for Toast Notification */
    </style>

    {{-- START: Toast Notification (Non-Modal, Hilang Otomatis) --}}
    <div id="toast-notification" role="alert">
        <div class="toast-icon">
             {{-- Success Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <div class="toast-text-container">
            <p class="toast-title" id="toast-title">Berhasil!</p>
            <p class="toast-message" id="toast-message">Layanan telah ditambahkan ke keranjang.</p>
        </div>
        {{-- Close Button (Opsional, tapi bagus untuk kontrol pengguna) --}}
        <button type="button" class="ml-3 text-gray-400 hover:text-gray-900 transition-colors" onclick="hideToast()">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    {{-- END: Toast Notification --}}

    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        {{-- Kembali ke Halaman Sebelumnya --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}"
               class="text-gray-500 hover:text-primary transition-colors flex items-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Main Grid Layout --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200 grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- KOLOM KIRI: Gambar Layanan & Deskripsi --}}
            <div class="space-y-6">

                {{-- Bagian Gambar Layanan (Asli) --}}
                <div class="relative">
                    <div class="relative overflow-hidden rounded-xl">
                        @if ($service->images && count(json_decode($service->images)) > 0)
                            @php
                                $images = json_decode($service->images, true);
                            @endphp
                            <img id="main-image"
                                src="{{ asset('storage/' . $images[0]) }}"
                                alt="{{ $service->title }}"
                                class="image-display">

                            {{-- Navigasi Gambar --}}
                            @if (count($images) > 1)
                                <button id="prev-btn"
                                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/70 backdrop-blur-sm p-3 rounded-full hover:bg-white transition-colors text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6 text-gray-800"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button id="next-btn"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/70 backdrop-blur-sm p-3 rounded-full hover:bg-white transition-colors text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6 text-gray-800"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        @else
                            <div
                                class="w-full h-auto min-h-[500px] bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg">
                                Tidak Ada Gambar</div>
                        @endif
                    </div>

                    {{-- Indikator Gambar --}}
                    @if (count(json_decode($service->images ?? '[]')) > 1)
                        <div class="flex justify-center mt-4 space-x-2">
                            @foreach (json_decode($service->images) as $index => $img)
                                <div class="w-3 h-3 rounded-full cursor-pointer {{ $index === 0 ? 'bg-primary' : 'bg-gray-300' }}"
                                     data-index="{{ $index }}"></div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Deskripsi Layanan (Tetap di Kolom Kiri) --}}
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Deskripsi Layanan</h2>
                    <div class="text-gray-700 leading-relaxed prose max-w-none">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Detail Ringkas, Penyedia Jasa, Spesifikasi, Jaminan, & Aksi --}}
            <div class="space-y-6">
                {{-- Judul dan Harga --}}
                <div>
                    <h1 class="text-3xl font-extrabold mb-1 text-gray-900 leading-tight">{{ $service->title }}</h1>

                    {{-- Rating dan Ulasan --}}
                    <div class="flex items-center mb-4">
                        @php
                            $avgRating = $service->reviews->avg('rating');
                            $reviewCount = $service->reviews->count();
                        @endphp
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                     fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                            <span class="text-sm font-bold text-yellow-500 ml-2">({{ number_format($avgRating, 1) }})</span>
                        </div>
                        <span class="text-gray-500 ml-3 text-sm">({{ $reviewCount }} ulasan)</span>
                    </div>

                    @if ($service->discount_price && $service->discount_price > 0)
                        <p class="text-xl font-bold text-red-600 mb-1">
                            Rp {{ number_format($service->discount_price, 0, ',', '.') }}
                            <span class="text-xs font-normal bg-red-100 text-red-500 px-2 py-0.5 rounded-full ml-2">Diskon!</span>
                        </p>
                        <p class="text-sm text-gray-500 line-through mb-3">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-xl font-bold text-primary mb-3">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </p>
                    @endif

                </div>

                {{-- Detail Tambahan (Kategori/Subkategori) --}}
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-gray-700 p-2 bg-gray-100 rounded flex items-center">
                        {{-- MENGGANTI IKON SVG DENGAN GAMBAR PNG --}}
                        <img src="{{ asset('images/image-home.png') }}"
                             alt="Kategori"
                             class="w-4 h-4 object-contain mr-1 flex-shrink-0">

                        <span class="font-normal text-primary">{{ $service->subcategory->category->name ?? '-' }}</span>
                        / <span class="font-normal text-primary ml-1">{{ $service->subcategory->name ?? '-' }}</span>
                    </p>
                </div>
                {{-- END: Detail Tambahan (Kategori/Subkategori) --}}

                {{-- START: Identitas Penyedia Jasa --}}
                <div class="pt-4 border-y border-gray-200 py-6 text-center">
                    <h2 class="text-lg font-bold mb-4 text-gray-800">Penyedia Jasa</h2>
                    <img src="{{ $service->user && $service->user->profile_photo ? asset('storage/' . $service->user->profile_photo) : asset('images/profile-user.png') }}"
                         alt="{{ $service->user->full_name ?? 'N/A' }}"
                         class="w-20 h-20 object-cover rounded-full border-2 border-primary mx-auto mb-3" />
                    <p class="font-extrabold text-xl text-gray-900 leading-snug">
                        {{ $service->user->full_name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500 truncate mb-3">{{ $service->user->email ?? 'N/A' }}</p>

                    <p class="text-sm text-gray-700 italic mb-4 max-w-xs mx-auto">"{{ $service->user->bio ?? 'Bio belum tersedia.' }}"
                    </p>

                    <h3 class="font-bold text-gray-800 mb-2 border-t pt-3">Kontak & Jaringan</h3>
                    <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-600">
                        @if ($service->user->website)
                            <a href="{{ $service->user->website }}"
                               target="_blank"
                               class="flex items-center text-primary hover:text-primary/80 transition">
                                @php
                                    $linkSvgPath = public_path('images/link-alt.svg');
                                @endphp
                                @if (file_exists($linkSvgPath))
                                    <span class="social-icon-wrapper text-primary">
                                        {!! file_get_contents($linkSvgPath) !!}
                                    </span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101" />
                                    </svg>
                                @endif
                                <span class="ml-1 text-xs font-semibold">Website</span>
                            </a>
                        @endif
                        @if ($service->user->linkedin)
                            <a href="{{ $service->user->linkedin }}"
                               target="_blank"
                               class="flex items-center text-primary hover:text-primary/80 transition">
                                @php
                                    $linkedinSvgPath = public_path('images/linkedin.svg');
                                @endphp
                                @if (file_exists($linkedinSvgPath))
                                    <span class="social-icon-wrapper text-primary">
                                        {!! file_get_contents($linkedinSvgPath) !!}
                                    </span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.761s.784-1.76 1.75-1.76 1.75.79 1.75 1.76-.783 1.761-1.75 1.761zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                    </svg>
                                @endif
                                <span class="ml-1 text-xs font-semibold">LinkedIn</span>
                            </a>
                        @endif
                        @if ($service->user->instagram)
                            <a href="{{ $service->user->instagram }}"
                               target="_blank"
                               class="flex items-center text-primary hover:text-primary/80 transition">
                                @php
                                    $instagramSvgPath = public_path('images/instagram.svg');
                                @endphp
                                @if (file_exists($instagramSvgPath))
                                    <span class="social-icon-wrapper text-primary">
                                        {!! file_get_contents($instagramSvgPath) !!}
                                    </span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                        <path d="M15 12c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z"></path>
                                        <line x1="16.5" y1="7.5" x2="16.5" y2="7.5"></line>
                                    </svg>
                                @endif
                                <span class="ml-1 text-xs font-semibold">Instagram</span>
                            </a>
                        @endif
                    </div>

                    {{-- START: TOMBOL HUBUNGI PENJUAL (LOKASI BARU) --}}
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        @if (auth()->check() && auth()->id() !== $service->user_id)
                            {{-- TOMBOL HUBUNGI PENJUAL (LOGGED IN) --}}
                            <a href="{{ route('conversations.start') }}"
                               onclick="event.preventDefault(); document.getElementById('start-chat-{{ $service->id }}').submit();"
                               class="w-full flex items-center justify-center gap-2 bg-white border border-primary text-primary font-bold py-3 px-4 rounded-lg hover:bg-gray-100 transition-colors duration-300 text-base">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z" />
                                </svg>
                                Hubungi Penjual
                            </a>

                            <form id="start-chat-{{ $service->id }}"
                                action="{{ route('conversations.start') }}"
                                method="POST"
                                class="hidden">
                                @csrf
                                <input type="hidden"
                                    name="seller_id"
                                    value="{{ $service->user->id }}" />
                                <input type="hidden"
                                    name="product_id"
                                    value="{{ $service->id }}" />
                            </form>
                        @else
                            @if (!auth()->check() || auth()->id() !== $service->user_id)
                                {{-- TOMBOL TAMBAH KE KERANJANG (LOGIN PROMPT) --}}
                                <a href="{{ route('login') }}"
                                   class="w-full flex items-center justify-center gap-2 bg-accent text-primary font-bold py-3 px-4 rounded-lg hover:bg-accent/80 transition-colors duration-300 text-base">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tambahkan ke Keranjang (Login)
                                </a>

                                {{-- TOMBOL PESAN SEKARANG (LOGIN PROMPT) --}}
                                <a href="{{ route('login') }}"
                                   class="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-300 text-base">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Pesan Sekarang (Login)
                                </a>
                            @endif
                        @endif
                    </div>
                    {{-- END: TOMBOL HUBUNGI PENJUAL (LOKASI BARU) --}}

                </div>
                {{-- END: Identitas Penyedia Jasa --}}

                {{-- START: Detail Spesifikasi --}}
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Spesifikasi</h2>
                    <ul class="text-gray-700 space-y-3 text-base">
                        <li class="flex items-center gap-2 border-b pb-2"><span class="font-semibold w-32">Jenis Pekerjaan:</span>
                            <span class="text-primary font-bold">{{ $service->job_type ?? '-' }}</span></li>
                        <li class="flex items-center gap-2 border-b pb-2"><span class="font-semibold w-32">Pengalaman:</span>
                            {{ $service->experience ?? '-' }}</li>
                        <li class="flex items-center gap-2 border-b pb-2"><span class="font-semibold w-32">Alamat:</span>
                            {{ $service->address ?? '-' }}</li>
                        <li class="flex items-center gap-2"><span class="font-semibold w-32">Tipe Layanan:</span>
                            <span class="text-primary font-bold">{{ ucfirst($service->service_type ?? '-') }}</span>
                        </li>
                    </ul>
                </div>
                {{-- END: Detail Spesifikasi --}}

                {{-- Jaminan Kualitas --}}
                <div class="p-4 bg-primary/10 rounded-lg border border-primary/20 text-primary">
                    <p class="font-bold mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Jaminan Kualitas
                    </p>
                    <p class="text-sm text-gray-700">Kami menjamin setiap layanan yang terdaftar telah diverifikasi.
                        Jika ada masalah, hubungi Layanan Pelanggan kami untuk bantuan.</p>
                </div>

                {{-- Tombol Aksi (Tinggal Tambahkan ke Keranjang & Pesan Sekarang) --}}
                <div class="pt-4 space-y-4 border-t border-gray-200">
                    @if (auth()->check() && auth()->id() !== $service->user_id)
                        {{-- TOMBOL TAMBAH KE KERANJANG --}}
                        <form id="add-to-cart-form" action="{{ route('cart.add', $service->slug) }}"
                              method="POST">
                            @csrf
                            <input type="hidden"
                                   name="quantity"
                                   value="1">
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 bg-accent text-primary font-bold py-3 px-4 rounded-lg hover:bg-accent/80 transition-colors duration-300 text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Tambahkan ke Keranjang
                            </button>
                        </form>

                        {{-- TOMBOL PESAN SEKARANG --}}
                        <a href="{{ route('orders.create', ['service' => $service->slug]) }}"
                           class="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-300 text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Pesan Sekarang
                        </a>
                    @else
                        @if (!auth()->check() || auth()->id() !== $service->user_id)
                            {{-- TOMBOL TAMBAH KE KERANJANG (LOGIN PROMPT) --}}
                            <a href="{{ route('login') }}"
                               class="w-full flex items-center justify-center gap-2 bg-accent text-primary font-bold py-3 px-4 rounded-lg hover:bg-accent/80 transition-colors duration-300 text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Tambahkan ke Keranjang (Login)
                            </a>

                            {{-- TOMBOL PESAN SEKARANG (LOGIN PROMPT) --}}
                            <a href="{{ route('login') }}"
                               class="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-300 text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Pesan Sekarang (Login)
                            </a>
                        @endif
                    @endif
                </div>

            </div>
        </div>

        {{-- Ulasan Pelanggan --}}
        {{-- ... (Ulasan Pelanggan dipertahankan) ... --}}

        {{-- Jasa Lainnya (Bagian yang Dimodifikasi) --}}
        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-5 text-gray-900">Jasa Lainnya</h2>
            {{-- MODIFIKASI GRID: grid-cols-2 di mobile --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($services->filter(fn($s) => $s->slug !== $service->slug)->take(4) as $otherService)
                    @php
                        $images = json_decode($otherService->images, true);
                        $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                        $profilePhoto =
                            $otherService->user && $otherService->user->profile_photo
                                ? asset('storage/' . $otherService->user->profile_photo)
                                : asset('images/profile-user.png');
                        $avgRating = $otherService->avg_rating;
                    @endphp
                    <a href="{{ route('services.show', $otherService->slug) }}"
                       class="normal-service-card block">
                        <div class="relative">
                            @if ($mainImage)
                                {{-- MODIFIKASI TINGGI GAMBAR: h-32 di mobile --}}
                                <img src="{{ $mainImage }}"
                                    alt="{{ $otherService->title }}"
                                    class="w-full h-32 sm:h-40 object-cover">
                            @else
                                <div class="w-full h-32 sm:h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                                    Tidak Ada Gambar</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-lg mb-1 text-gray-900 truncate hover:text-primary transition-colors">{{ $otherService->title }}</p>

                            @if ($otherService->discount_price && $otherService->discount_price > 0)
                                <p class="text-lg font-bold text-red-600 mb-1">
                                    Rp {{ number_format($otherService->discount_price, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 line-through mb-2">
                                    Rp {{ number_format($otherService->price, 0, ',', '.') }}
                                </p>
                            @else
                                <p class="text-lg font-bold text-green-600 mb-2">
                                    Rp {{ number_format($otherService->price, 0, ',', '.') }}
                                </p>
                            @endif

                            {{-- MODIFIKASI DESKRIPSI: Hilang di mobile (hidden sm:block) --}}
                            <p class="text-gray-500 mb-4 line-clamp-2 text-sm hidden sm:block">
                                {{ Str::words(strip_tags($otherService->description), 8, '...') }}
                            </p>

                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                <img src="{{ $profilePhoto }}"
                                    alt="{{ $otherService->user->full_name ?? 'N/A' }}"
                                    class="w-7 h-7 rounded-full object-cover">
                                <span
                                    class="text-gray-700 font-semibold">{{ $otherService->user->full_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-4">Tidak ada jasa lain untuk ditampilkan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Script untuk Navigasi Gambar (Tidak Berubah) --}}
    @if ($service->images && count(json_decode($service->images)) > 1)
        @php $images = json_decode($service->images, true); @endphp
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const images = @json($images);
                const mainImage = document.getElementById('main-image');
                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');
                const imageIndicators = document.querySelectorAll('[data-index]');
                let currentIndex = 0;

                function showImage(index) {
                    currentIndex = index;
                    mainImage.src = '{{ asset('storage') }}' + '/' + images[currentIndex];
                    updateIndicators();
                }

                function updateIndicators() {
                    imageIndicators.forEach(indicator => {
                        if (parseInt(indicator.dataset.index) === currentIndex) {
                            indicator.classList.remove('bg-gray-300');
                            indicator.classList.add('bg-primary');
                        } else {
                            indicator.classList.remove('bg-primary');
                            indicator.classList.add('bg-gray-300');
                        }
                    });
                }

                // Event listener untuk indikator
                imageIndicators.forEach(indicator => {
                    indicator.addEventListener('click', () => {
                        const index = parseInt(indicator.dataset.index);
                        showImage(index);
                    });
                });

                // Event listener untuk tombol navigasi
                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
                        showImage(currentIndex);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
                        showImage(currentIndex);
                    });
                }

                // Initial setup
                showImage(currentIndex);
            });
        </script>
    @endif

    {{-- START: Script untuk Notifikasi Keranjang dengan Toast --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addToCartForm = document.getElementById('add-to-cart-form');
            const toastNotification = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            const toastTitle = document.getElementById('toast-title');
            const toastIcon = toastNotification.querySelector('.toast-icon svg');

            let dismissTimeout;

            // Fungsi untuk menyembunyikan Toast
            window.hideToast = function() {
                clearTimeout(dismissTimeout);
                toastNotification.classList.remove('show');
            }

            // Fungsi untuk menampilkan Toast
            function showToast(message, isSuccess = true) {
                // Atur pesan dan gaya
                toastMessage.textContent = message;

                if (isSuccess) {
                    toastTitle.textContent = 'Berhasil Ditambahkan!';
                    // Pastikan ikon berwarna hijau (menggunakan CSS class .toast-icon)
                    toastIcon.parentNode.style.color = '#10b981'; // Green-500
                    toastTitle.style.color = '#10b981'; // Green-500
                    toastNotification.style.borderColor = '#10b981';
                } else {
                    toastTitle.textContent = 'Gagal!';
                    // Atur untuk Error/Gagal (Misalnya menggunakan warna merah)
                    toastIcon.parentNode.style.color = '#ef4444'; // Red-500
                    toastTitle.style.color = '#ef4444'; // Red-500
                    toastNotification.style.borderColor = '#ef4444';
                    // (Anda mungkin perlu mengganti path SVG jika ingin ikon 'X' untuk error)
                }

                // Tampilkan Toast
                toastNotification.classList.add('show');

                // Set auto-dismiss setelah 3 detik
                dismissTimeout = setTimeout(hideToast, 3000);
            }

            if (addToCartForm) {
                // Gunakan Fetch API untuk mengirim form secara AJAX
                addToCartForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = e.target;
                    const url = form.action;
                    const formData = new FormData(form);

                    hideToast(); // Sembunyikan toast sebelumnya

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        // Coba parse JSON
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            return response.json();
                        }

                        // Jika bukan JSON, dan status OK, asumsikan berhasil
                        if (response.ok) {
                            return { success: true, message: 'Layanan berhasil ditambahkan ke keranjang.' };
                        }

                        // Jika status bukan OK, lempar error
                        throw new Error('Terjadi kesalahan saat menambahkan ke keranjang.');
                    })
                    .then(data => {
                        // Tampilkan Toast berdasarkan respons
                        if (data.success) {
                            showToast(data.message || 'Layanan berhasil ditambahkan ke keranjang.');
                        } else {
                            // Tampilkan notifikasi gagal
                            showToast(data.message || 'Gagal menambahkan layanan ke keranjang.', false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Tampilkan notifikasi error
                        showToast('Terjadi kesalahan koneksi atau server.', false);
                    });
                });
            }
        });
    </script>
    {{-- END: Script untuk Notifikasi Keranjang dengan Toast --}}
</x-app-layout>
