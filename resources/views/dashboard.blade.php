<x-app-layout>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc;
        }

        .bg-primary {
            background-color: #2b3cd7;
        }

        .text-primary {
            color: #2b3cd7;
        }

        .border-primary {
            border-color: #2b3cd7;
        }

        .bg-accent {
            background-color: #ffd231;
        }

        .text-accent {
            color: #ffd231;
        }

        .text-red-500 {
            color: #ef4444;
        }

        .focus\:ring-primary:focus {
            --tw-ring-color: #2b3cd7;
        }

        #autocomplete-results li:hover {
            background-color: #eef2ff;
        }

        .animated-underline {
            position: relative;
            display: inline-block;
        }

        .animated-underline::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: currentColor;
            transform-origin: right;
            transform: scaleX(0);
            transition: transform 0.3s ease-out;
        }

        .animated-underline:hover::after {
            transform-origin: left;
            transform: scaleX(1);
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2b3cd7;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-container input {
            padding-right: 3rem;
            /* Perbaikan: Atur tinggi input yang konsisten */
            height: 52px;
        }

        .search-container .search-icon {
            position: absolute;
            right: 1rem;
            color: #9ca3af;
            transition: color 0.3s;
        }

        .search-container input:focus+.search-icon {
            color: #2b3cd7;
        }

        .nearby-input {
            /* Perbaikan: Atur tinggi input yang konsisten */
            height: 52px;
        }

        /* START: Custom CSS for Swiper (Final Fix) */
        .swiper-pagination-bullet {
            background: #cbd5e1;
            opacity: 1;
            width: 8px;
            height: 8px;
        }

        .swiper-pagination-bullet-active {
            background: #2b3cd7;
            width: 20px;
            border-radius: 4px;
            transition: width 0.3s;
        }

        /* Perbaikan untuk mencegah tombol bawaan Swiper bertabrakan dengan SVG kustom */
        .swiper-button-prev,
        .swiper-button-next {
            /* Gunakan properti Swiper untuk positioning utama */
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            cursor: pointer;

            /* Overwrite/Perbaikan untuk tampilan */
            width: 40px !important;
            height: 40px !important;
            color: transparent !important;
            /* Hilangkan ikon default Swiper */
            margin-top: 0 !important;
            /* Reset margin default */
        }

        .swiper-button-prev::after,
        .swiper-button-next::after {
            content: '' !important;
            /* Pastikan pseudo-element kosong */
        }

        /* END: Custom CSS for Swiper */

        /* CSS untuk menghilangkan scrollbar horizontal */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>

    {{-- Link CSS Swiper dari welcome.blade.php --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <div id="notification-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-sm text-center">
            <p id="modal-message" class="mb-4 text-gray-700"></p>
            <button id="modal-close-btn"
                class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:opacity-90 transition-opacity">
                OK
            </button>
        </div>
    </div>

    {{-- START: Price Filter Modal (Pop-up) --}}
    <div id="price-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md">
            <h3 class="text-xl font-extrabold mb-4 text-gray-900">Atur Rentang Harga</h3>
            <form id="price-form-modal">
                <div class="mb-4">
                    <label for="price_min_modal" class="block text-sm font-semibold text-gray-700 mb-2">Harga
                        Minimum (Rp)</label>
                    <input type="number" id="price_min_modal" name="price_min" placeholder="e.g. 50000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 font-semibold focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        value="{{ request('price_min') }}">
                </div>
                <div class="mb-6">
                    <label for="price_max_modal" class="block text-sm font-semibold text-gray-700 mb-2">Harga
                        Maksimum (Rp)</label>
                    <input type="number" id="price_max_modal" name="price_max" placeholder="e.g. 500000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 font-semibold focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        value="{{ request('price_max') }}">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" id="price-modal-reset"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-bold">Reset</button>
                    <button type="submit"
                        class="bg-primary text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition-opacity">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- END: Price Filter Modal (Pop-up) --}}

    {{-- START: LOCATION SEARCH MODAL (Pop-up Baru) --}}
    <div id="location-search-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        {{-- Perubahan Ukuran Modal Container: max-w-lg --}}
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-lg relative">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-extrabold text-gray-900">Cari Layanan di Lokasi Anda</h3>
                {{-- Tombol Close (X) --}}
                <button type="button" id="location-modal-close-x"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="location-form-modal" action="{{ route('services.nearby') }}" method="get" class="flex flex-col">
                <div class="relative flex-grow">
                    <label for="address-input-modal" class="block text-sm font-semibold text-gray-700 mb-2">Masukkan
                        Alamat</label>
                    <input type="text" id="address-input-modal" placeholder="Ketik alamat Anda..."
                        class="w-full p-3 border border-gray-300 rounded-lg font-semibold focus:ring-2 focus:ring-primary focus:border-transparent transition bg-gray-50"
                        autocomplete="off" required>
                    <input type="hidden" name="lat" id="lat-modal">
                    <input type="hidden" name="lng" id="lng-modal">
                    <ul id="autocomplete-results-modal"
                        class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg max-h-60 overflow-y-auto z-10 list-none p-0 m-0 hidden">
                    </ul>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    {{-- Tombol Batal dipindahkan ke sini dan menggunakan ID penutup X untuk fungsionalitasnya --}}
                    <button type="button" id="location-modal-close-button"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-bold">Batal</button>
                    <button type="submit"
                        class="bg-primary text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition-opacity flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mr-2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- END: LOCATION SEARCH MODAL --}}

    @php
        // Data dummy untuk banner slider (Dari welcome.blade.php)
        $banners = [
            [
                'image' => 'banner-pertama.png',
                'link' => '/login',
                'alt' => 'Promo Spesial Awal Bulan',
            ],
            [
                'image' => 'banner-kedua.png',
                'link' => '/service/apply',
                'alt' => 'Layanan Terpopuler Minggu Ini',
            ],
        ];
    @endphp

    @php
        // Cek apakah ada parameter filter/pencarian yang aktif
        $isSearchingOrFiltering =
            request('search') ||
            request('category') || // Menambahkan category karena tab kategori juga termasuk filter
            request('city') ||
            request('status') ||
            request('price_min') ||
            request('price_max') ||
            request('discount') ||
            request('rating_filter') ||
            (request('lat') && request('lng')); // Tambahkan cek untuk pencarian lokasi terdekat

        // Fungsi untuk menentukan sapaan waktu
        function getGreeting() {
            $hour = date('H');
            if ($hour >= 5 && $hour < 11) {
                return 'Selamat Pagi';
            } elseif ($hour >= 11 && $hour < 15) {
                return 'Selamat Siang';
            } elseif ($hour >= 15 && $hour < 18) {
                return 'Selamat Sore';
            } else {
                return 'Selamat Malam';
            }
        }

        // Definisikan daftar kota di sini
        $cities = [
            'Palembang',
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Medan',
            'Semarang',
            'Makassar',
            'Yogyakarta',
            'Bekasi',
            'Depok',
        ];

        // LOGIKA TAMBAHAN: Ambil Layanan Populer (Non-Highlight)
        // Pastikan variabel $services sudah didefinisikan oleh controller dan berisi semua layanan yang difilter.
        // Jika tidak, Anda mungkin perlu melakukan query di sini, contoh:
        // $allServices = \App\Models\Service::query()->/* ... apply filters ... */->get();
        // $services = $allServices; // Jika sudah difilter oleh controller.

        // Memisahkan layanan non-unggulan
        $nonHighlightServices = $services->filter(
            fn($s) => !($s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until)),
        );

        // Mengambil layanan paling populer (rating tertinggi) dari yang non-unggulan
        $popularServices = $nonHighlightServices->sortByDesc('avg_rating')->take(4);

        // Memisahkan layanan normal (selain unggulan dan populer yang sudah diambil)
        $normalServices = $services->filter(
            fn($s) => !($s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until))
        )->reject(
            fn($s) => $popularServices->contains($s)
        );
    @endphp

    <div class="container mx-auto p-4 md:p-8 max-w-7xl">

        {{-- START: Widget Sapaan Waktu Dinamis --}}
        <div class="mb-6">
            <h1 class="text-2xl font-black text-gray-900">{{ getGreeting() }}!</h1>
            <div class="text-xl text-primary font-extrabold">{{ optional(Auth::user())->full_name ?? 'Guest' }}</div>
        </div>
        {{-- END: Widget Sapaan Waktu Dinamis --}}

        {{-- START: Banner Swiper --}}
        {{-- Sembunyikan banner saat ada filter/pencarian aktif --}}
        @if (!$isSearchingOrFiltering)
            <div class="swiper mySwiper w-full h-auto rounded-xl shadow-lg mb-8 relative">
                <div class="swiper-wrapper">
                    @foreach ($banners as $banner)
                        <div class="swiper-slide min-h-32">
                            <a href="{{ $banner['link'] }}" class="block h-full">
                                {{-- Asumsi: images/banner-xxx.png ada di folder public/images/ --}}
                                <img src="{{ asset('images/' . $banner['image']) }}" alt="{{ $banner['alt'] }}"
                                    class="w-full h-full object-cover rounded-xl"
                                    onerror="this.onerror=null; this.src='{{ asset('images/default-banner.png') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination !bottom-2"></div>

                <div id="swiper-button-prev-custom"
                    class="swiper-button-prev !left-4 !w-10 !h-10 bg-white/70 rounded-full flex items-center justify-center backdrop-blur-sm shadow-md transition-opacity hover:opacity-100 opacity-80 cursor-pointer z-10 hidden md:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="#2b3cd7" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </div>
                <div id="swiper-button-next-custom"
                    class="swiper-button-next !right-4 !w-10 !h-10 bg-white/70 rounded-full flex items-center justify-center backdrop-blur-sm shadow-md transition-opacity hover:opacity-100 opacity-80 cursor-pointer z-10 hidden md:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="#2b3cd7" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
        @endif
        {{-- END: Banner Swiper --}}

        {{-- START: KATEGORI, SEARCH, & LOKASI (DALAM SATU BOX) --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 mb-8">

            {{-- DAFTAR KATEGORI --}}
            {{-- Menggunakan flex-nowrap dan overflow-x-auto untuk scroll horizontal --}}
            <div class="flex flex-nowrap overflow-x-auto gap-2 mb-6 pb-2 no-scrollbar">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition-colors duration-200 flex-shrink-0
                   {{ !request('category') ? 'bg-primary text-accent' : 'bg-gray-100 text-gray-700 hover:bg-primary hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Semua
                </a>

                @php
                    $categories = \App\Models\Category::all();
                    $categoryIcons = [
                        'Graphics & Design' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M432.5 82.3L382.4 132.4L507.7 257.7L557.8 207.6C579.7 185.7 579.7 150.3 557.8 128.4L511.7 82.3C489.8 60.4 454.4 60.4 432.5 82.3zM343.3 161.2L342.8 161.3L198.7 204.5C178.8 210.5 163 225.7 156.4 245.5L67.8 509.8C64.9 518.5 65.9 528 70.3 535.8L225.7 380.4C224.6 376.4 224.1 372.3 224.1 368C224.1 341.5 245.6 320 272.1 320C298.6 320 320.1 341.5 320.1 368C320.1 394.5 298.6 416 272.1 416C267.8 416 263.6 415.4 259.7 414.4L104.3 569.7C112.1 574.1 121.5 575.1 130.3 572.2L394.6 483.6C414.3 477 429.6 461.2 435.6 441.3L478.8 297.2L478.9 296.7L343.4 161.2z"/></svg>',
                        'Digital Marketing' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M544 72C544 58.7 533.3 48 520 48L418.2 48C404.9 48 394.2 58.7 394.2 72C394.2 85.3 404.9 96 418.2 96L462.1 96L350.8 207.3L255.7 125.8C246.7 118.1 233.5 118.1 224.5 125.8L112.5 221.8C102.4 230.4 101.3 245.6 109.9 255.6C118.5 265.6 133.7 266.8 143.7 258.2L240.1 175.6L336.5 258.2C346 266.4 360.2 265.8 369.1 256.9L496.1 129.9L496.1 173.8C496.1 187.1 506.8 197.8 520.1 197.8C533.4 197.8 544.1 187.1 544.1 173.8L544 72zM112 320C85.5 320 64 341.5 64 368L64 528C64 554.5 85.5 576 112 576L528 576C554.5 576 576 554.5 576 528L576 368C576 341.5 554.5 320 528 320L112 320zM159.3 376C155.9 396.1 140.1 412 119.9 415.4C115.5 416.1 111.9 412.5 111.9 408.1L111.9 376.1C111.9 371.7 115.5 368.1 119.9 368.1L151.9 368.1C156.3 368.1 160 371.7 159.2 376.1zM159.3 520.1C160 524.5 156.4 528.1 152 528.1L120 528.1C115.6 528.1 112 524.5 112 520.1L112 488.1C112 483.7 115.6 480 120 480.8C140.1 484.2 156 500 159.4 520.2zM520 480.7C524.4 480 528 483.6 528 488L528 520C528 524.4 524.4 528 520 528L488 528C483.6 528 479.9 524.4 480.7 520C484.1 499.9 499.9 484 520.1 480.6zM480.7 376C480 371.6 483.6 368 488 368L520 368C524.4 368 528 371.6 528 376L528 408C528 412.4 524.4 416.1 520 415.3C499.9 411.9 484 396.1 480.6 375.9zM256 448C256 412.7 284.7 384 320 384C355.3 384 384 412.7 384 448C384 483.3 355.3 512 320 512C284.7 512 256 483.3 256 448z"/></svg>',
                        'Writing & Translation' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M96 128C60.7 128 32 156.7 32 192L32 448C32 483.3 60.7 512 96 512L544 512C579.3 512 608 483.3 608 448L608 192C608 156.7 579.3 128 544 128L96 128zM112 192L144 192C152.8 192 160 199.2 160 208L160 240C160 248.8 152.8 256 144 256L112 256C103.2 256 96 248.8 96 240L96 208C96 199.2 103.2 192 112 192zM96 304C96 295.2 103.2 288 112 288L144 288C152.8 288 160 295.2 160 304L160 336C160 344.8 152.8 352 144 352L112 352C103.2 352 96 344.8 96 336L96 304zM208 192L240 192C248.8 192 256 199.2 256 208L256 240C256 248.8 248.8 256 240 256L208 256C199.2 256 192 248.8 192 240L192 208C192 199.2 199.2 192 208 192zM192 304C192 295.2 199.2 288 208 288L240 288C248.8 288 256 295.2 256 304L256 336C256 344.8 248.8 352 240 352L208 352C199.2 352 192 344.8 192 336L192 304zM208 384L432 384C440.8 384 448 391.2 448 400L448 432C448 440.8 440.8 448 432 448L208 448C199.2 448 192 440.8 192 432L192 400C192 391.2 199.2 384 208 384zM288 208C288 199.2 295.2 192 304 192L336 192C344.8 192 352 199.2 352 208L352 240C352 248.8 344.8 256 336 256L304 256C295.2 256 288 248.8 288 240L288 208zM304 288L336 288C344.8 288 352 295.2 352 304L352 336C352 344.8 344.8 352 336 352L304 352C295.2 352 288 344.8 288 336L288 304C288 295.2 295.2 288 304 288zM384 208C384 199.2 391.2 192 400 192L432 192C440.8 192 448 199.2 448 208L448 240C448 248.8 440.8 256 432 256L400 256C391.2 256 384 248.8 384 240L384 208zM400 288L432 288C440.8 288 448 295.2 448 304L448 336C448 344.8 440.8 352 432 352L400 352C391.2 352 384 344.8 384 336L384 304C384 295.2 391.2 288 400 288zM480 208C480 199.2 487.2 192 496 192L528 192C536.8 192 544 199.2 544 208L544 240C544 248.8 536.8 256 528 256L496 256C487.2 256 480 248.8 480 240L480 208zM496 288L528 288C536.8 288 544 295.2 544 304L544 336C544 344.8 536.8 352 528 352L496 352C487.2 352 480 344.8 480 336L480 304C480 295.2 487.2 288 496 288z"/></svg>',
                        'Video & Animation' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L384 512C419.3 512 448 483.3 448 448L448 192C448 156.7 419.3 128 384 128L128 128zM496 400L569.5 458.8C573.7 462.2 578.9 464 584.3 464C597.4 464 608 453.4 608 440.3L608 199.7C608 186.6 597.4 176 584.3 176C578.9 176 573.7 177.8 569.5 181.2L496 240L496 400z"/></svg>',
                        'Music & Audio' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M532 71C539.6 77.1 544 86.3 544 96L544 400C544 444.2 501 480 448 480C395 480 352 444.2 352 400C352 355.8 395 320 448 320C459.2 320 470 321.6 480 324.6L480 207.9L256 257.7L256 464C256 508.2 213 544 160 544C107 544 64 508.2 64 464C64 419.8 107 384 160 384C171.2 384 182 385.6 192 388.6L192 160C192 145 202.4 132 217.1 128.8L505.1 64.8C514.6 62.7 524.5 65 532.1 71.1z"/></svg>',
                        'Programming & Tech' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M392.8 65.2C375.8 60.3 358.1 70.2 353.2 87.2L225.2 535.2C220.3 552.2 230.2 569.9 247.2 574.8C264.2 579.7 281.9 569.8 286.8 552.8L414.8 104.8C419.7 87.8 409.8 70.1 392.8 65.2zM457.4 201.3C444.9 213.8 444.9 234.1 457.4 246.6L530.8 320L457.4 393.4C444.9 405.9 444.9 426.2 457.4 438.7C469.9 451.2 490.2 451.2 502.7 438.7L598.7 342.7C611.2 330.2 611.2 309.9 598.7 297.4L502.7 201.4C490.2 188.9 469.9 188.9 457.4 201.4zM182.7 201.3C170.2 188.8 149.9 188.8 137.4 201.3L41.4 297.3C28.9 309.8 28.9 330.1 41.4 342.6L137.4 438.6C149.9 451.1 170.2 451.1 182.7 438.6C195.2 426.1 195.2 405.8 182.7 393.3L109.3 320L182.6 246.6C195.1 234.1 195.1 213.8 182.6 201.3z"/></svg>',
                        'Business' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M264 112L376 112C380.4 112 384 115.6 384 120L384 160L256 160L256 120C256 115.6 259.6 112 264 112zM208 120L208 160L128 160C92.7 160 64 188.7 64 224L64 320L576 320L576 224C576 188.7 547.3 160 512 160L432 160L432 120C432 89.1 406.9 64 376 64L264 64C233.1 64 208 89.1 208 120zM576 368L384 368L384 384C384 401.7 369.7 416 352 416L288 416C270.3 416 256 401.7 256 384L256 368L64 368L64 480C64 515.3 92.7 544 128 544L512 544C547.3 544 576 515.3 576 480L576 368z"/></svg>',
                        'Industries' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M96 96C78.3 96 64 110.3 64 128L64 496C64 522.5 85.5 544 112 544L528 544C554.5 544 576 522.5 576 496L576 216.2C576 198 556.6 186.5 540.6 195.1L384 279.4L384 216.2C384 198 364.6 186.5 348.6 195.1L192 279.4L192 128C192 110.3 177.7 96 160 96L96 96z"/></svg>',
                        'Perbaikan Elektronik' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M192 32C209.7 32 224 46.3 224 64L224 160L352 160L352 64C352 46.3 366.3 32 384 32C401.7 32 416 46.3 416 64L416 160L480 160C497.7 160 512 174.3 512 192C512 209.7 497.7 224 480 224L480 272.7C381.4 280.8 304 363.4 304 464C304 491.3 309.7 517.3 320 540.9L320 544C320 561.7 305.7 576 288 576C270.3 576 256 561.7 256 544L256 477.3C165.2 462.1 96 383.1 96 288L96 224C78.3 224 64 209.7 64 192C64 174.3 78.3 160 96 160L160 160L160 64C160 46.3 174.3 32 192 32zM496 320C575.5 320 640 384.5 640 464C640 543.5 575.5 608 496 608C416.5 608 352 543.5 352 464C352 384.5 416.5 320 496 320zM555.3 427.3C561.5 421.1 561.5 410.9 555.3 404.7C549.1 398.5 538.9 398.5 532.7 404.7L496 441.4L459.3 404.7C453.1 398.5 442.9 398.5 436.7 404.7C430.5 410.9 430.5 517.1 436.7 523.3C442.9 529.5 453.1 529.5 459.3 523.3L496 486.6L532.7 523.3C538.9 529.5 549.1 529.5 555.3 523.3C561.5 517.1 561.5 506.9 555.3 500.7L518.6 464L555.3 427.3z"/></svg>',
                        'Jasa Smartphone' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M208 64C172.7 64 144 92.7 144 128L144 512C144 547.3 172.7 576 208 576L432 576C467.3 576 496 547.3 496 512L496 128C496 92.7 467.3 64 432 64L208 64zM280 480L360 480C373.3 480 384 490.7 384 504C384 517.3 373.3 528 360 528L280 528C266.7 528 256 517.3 256 504C256 490.7 266.7 480 280 480z"/></svg>',
                        'Jasa Akademik' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M32 256C32 220.7 60.7 192 96 192L160 192L287.9 76.9C306.2 60.5 333.9 60.5 352.1 76.9L480 192L544 192C579.3 192 608 220.7 608 256L608 512C608 547.3 579.3 576 544 576L96 576C60.7 576 32 547.3 32 512L32 256zM256 440L256 528L384 528L384 440C384 417.9 366.1 400 344 400L296 400C273.9 400 256 417.9 256 440zM144 448C152.8 448 160 440.8 160 432L160 400C160 391.2 152.8 384 144 384L112 384C103.2 384 96 391.2 96 400L96 432C96 440.8 103.2 448 112 448L144 448zM160 304L160 272C160 263.2 152.8 256 144 256L112 256C103.2 256 96 263.2 96 272L96 304C96 312.8 103.2 320 112 320L144 320C152.8 320 160 312.8 160 304zM528 448C536.8 448 544 440.8 544 432L544 400C544 391.2 536.8 384 528 384L496 384C487.2 384 480 391.2 480 400L480 432C480 440.8 487.2 448 496 448L528 448zM544 304L544 272C544 263.2 536.8 256 528 256L496 256C487.2 256 480 263.2 480 272L480 304C480 312.8 487.2 320 496 320L528 320C536.8 320 544 312.8 544 304zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"/></svg>',
                        'Kreatif & Digital' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M176 168C189.3 168 200 157.3 200 144C200 130.7 189.3 120 176 120C162.7 120 152 130.7 152 144C152 157.3 162.7 168 176 168zM256 144C256 176.8 236.3 205 208 217.3L208 288L384 288C410.5 288 432 266.5 432 240L432 217.3C403.7 205 384 176.8 384 144C384 99.8 419.8 64 464 64C508.2 64 544 99.8 544 144C544 176.8 524.3 205 496 217.3L496 240C496 301.9 445.9 352 384 352L208 352L208 422.7C236.3 435 256 463.2 256 496C256 540.2 220.2 576 176 576C131.8 576 96 540.2 96 496C96 463.2 115.7 435 144 422.7L144 217.4C115.7 205 96 176.8 96 144C96 99.8 131.8 64 176 64C220.2 64 256 99.8 256 144zM488 144C488 130.7 477.3 120 464 120C450.7 120 440 130.7 440 144C440 157.3 450.7 168 464 168C477.3 168 488 157.3 488 144zM176 520C189.3 520 200 509.3 200 496C200 482.7 189.3 472 176 472C162.7 472 152 482.7 152 496C152 509.3 162.7 520 176 520z"/></svg>',
                        'Konsultasi & Lainnya' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M320 544C461.4 544 576 436.5 576 304C576 171.5 461.4 64 320 64C178.6 64 64 171.5 64 304C64 358.3 83.2 408.3 115.6 448.5L66.8 540.8C62 549.8 63.5 560.8 70.4 568.3C77.3 575.8 88.2 578.1 97.5 574.1L215.9 523.4C247.7 536.6 282.9 544 320 544zM192 272C209.7 272 224 286.3 224 304C224 321.7 209.7 336 192 336C174.3 336 160 321.7 160 304C160 286.3 174.3 272 192 272zM320 272C337.7 272 352 286.3 352 304C352 321.7 337.7 336 320 336C302.3 336 288 321.7 288 304C288 286.3 302.3 272 320 272zM416 304C416 286.3 430.3 272 448 272C465.7 272 480 286.3 480 304C480 321.7 465.7 336 448 336C430.3 336 416 321.7 416 304z"/></svg>',
                        'Les Musik' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M529 71C519.6 61.6 504.4 61.6 495.1 71L447 119C444.6 121.4 442.7 124.3 441.5 127.5L426.1 168.5L348.6 246.1C303.5 216.7 249.3 215.9 217.6 247.7C206.6 258.7 199.6 272.3 196.2 287.3C192.5 303.9 177.1 318 160.1 318.9C134.5 320.2 110.8 329.6 92.8 347.5C48 392.3 56.4 473.3 111.5 528.4C166.6 583.5 247.6 592 292.4 547.2C310.3 529.3 319.8 505.5 321 479.9C321.9 462.9 336 447.6 352.6 443.8C367.6 440.4 381.2 433.3 392.2 422.4C424 390.6 423.2 336.5 393.8 291.4L471.4 213.8L512.4 198.4C515.6 197.2 518.5 195.3 520.9 192.9L568.9 144.9C578.3 135.5 578.3 120.3 568.9 111L529 71zM272 320C298.5 320 320 341.5 320 368C320 394.5 298.5 416 272 416C245.5 416 224 394.5 224 368C224 341.5 245.5 320 272 320z"/></svg>',
                        'Lifestyle' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 640 640"><path d="M341.5 45.1C337.4 37.1 329.1 32 320.1 32C311.1 32 302.8 37.1 298.7 45.1L225.1 189.3L65.2 214.7C56.3 216.1 48.9 222.4 46.1 231C43.3 239.6 45.6 249 51.9 255.4L166.3 369.9L141.1 529.8C139.7 538.7 143.4 547.7 150.7 553C158 558.3 167.6 559.1 175.7 555L320.1 481.6L464.4 555C472.4 559.1 482.1 558.3 489.4 553C496.7 547.7 500.4 538.8 499 529.8L473.7 369.9L588.1 255.4C594.5 249 596.7 239.6 593.9 231C591.1 222.4 583.8 216.1 574.8 214.7L415 189.3L341.5 45.1z"/></svg>',
                        'default' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14a7 7 0 00-7 7m14 0a7 7 0 00-7-7m-7 0a7 7 0 017-7m0 7a7 7 0 007-7m-7 7a7 7 0 01-7 7m0-14a7 7 0 017-7M12 7v10" /></svg>',
                    ];
                @endphp
                @foreach ($categories as $cat)
                    <a href="{{ route('dashboard', ['category' => $cat->id]) }}"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition-colors duration-200 flex-shrink-0
                   {{ request('category') == $cat->id ? 'bg-primary text-accent' : 'bg-gray-100 text-gray-700 hover:bg-primary hover:text-white' }}">
                        @php echo $categoryIcons[$cat->name] ?? $categoryIcons['default']; @endphp
                        <span>{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>

            {{-- SEARCH DAN LOKASI --}}
            <h2 class="text-xl font-extrabold mb-4 text-gray-900 border-t pt-4">Temukan Layanan Terbaik</h2>

            <div class="flex gap-4 items-end">

                {{-- Container untuk Search Bar Kata Kunci --}}
                <div class="flex-grow relative">
                    <form action="{{ route('dashboard') }}" method="GET" id="main-search-form" class="relative">
                        <p class="text-gray-600 font-semibold mb-2">Cari berdasarkan kata kunci atau temukan layanan di dekat Anda.</p>
                        <div class="search-container">
                            <input type="text" name="search" id="main-search-input" placeholder="Cari layanan apa pun..."
                                value="{{ request('search') }}"
                                class="w-full py-2 pl-4 border border-gray-300 rounded-lg bg-gray-50 font-semibold focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                autocomplete="off">
                            <button type="submit" class="search-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    {{-- START: Rekomendasi Pencarian Cepat (Hidden by default) --}}
                    {{-- Posisi absolute agar muncul di atas konten lain, z-20 agar di atas input --}}
                    <div id="quick-search-recommendations"
                        class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-20 p-3 hidden">
                        <p class="text-sm font-bold text-gray-800 mb-2">Pencarian Cepat:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($categories->take(4) as $cat)
                                <a href="{{ route('dashboard', ['category' => $cat->id]) }}"
                                    class="text-xs font-semibold bg-gray-100 text-primary px-3 py-1 rounded-full hover:bg-primary hover:text-white transition">
                                    #{{ $cat->name }}
                                </a>
                            @endforeach
                            <a href="{{ route('dashboard', ['discount' => 1]) }}"
                                class="text-xs font-semibold bg-red-100 text-red-600 px-3 py-1 rounded-full hover:bg-red-600 hover:text-white transition">
                                #PromoDiskon
                            </a>
                        </div>
                    </div>
                    {{-- END: Rekomendasi Pencarian Cepat --}}
                </div>

                {{-- Tombol Pencarian Lokasi Terdekat (DIKECILKAN SEUKURAN ICON) --}}
                <div class="flex flex-col items-center">
                    <p class="text-gray-600 font-semibold mb-2 text-center whitespace-nowrap">Terdekat</p>
                    {{-- Tombol yang memicu modal lokasi, diatur ukuran tetap w-12 h-12 --}}
                    <button type="button" id="open-location-modal"
                        class="bg-primary text-white font-extrabold rounded-lg shadow-md hover:opacity-90 transition-all flex items-center justify-center w-12 h-12 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="sr-only">Cari Layanan Terdekat</span>
                    </button>
                </div>
            </div>
        </div>
        {{-- END: KATEGORI, SEARCH, & LOKASI (DALAM SATU BOX) --}}


        @auth
            @if (auth()->user()->role === 'admin')
                <div class="mb-4 flex flex-wrap gap-4">
                    <a href="{{ route('categories.index') }}"
                        class="animated-underline text-gray-700 hover:text-primary font-bold transition-colors">Kelola
                        Kategori</a>
                    <a href="{{ route('subcategories.index') }}"
                        class="animated-underline text-gray-700 hover:text-primary font-bold transition-colors">Kelola
                        Subkategori</a>
                </div>
            @endif
        @endauth


        {{-- Filter Lanjutan: Hanya muncul saat ada pencarian/filter aktif --}}
        @if ($isSearchingOrFiltering)
            <form id="secondary-filter-form" method="GET" action="{{ route('dashboard') }}" class="mb-8">
                <h2 class="text-xl font-extrabold text-gray-800 mb-6 border-b pb-4">Temukan Layanan Sesuai Filter</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

                    {{-- **Input Hidden untuk mempertahankan nilai 'search'** --}}
                    <input type="hidden" name="search" id="secondary-search-hidden" value="{{ request('search') }}">
                    {{-- **Input Hidden untuk mempertahankan nilai 'category' (dari tab)** --}}
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    {{-- **Input Hidden untuk mempertahankan nilai 'lat' dan 'lng' (dari nearby search)** --}}
                    @if (request('lat') && request('lng'))
                        <input type="hidden" name="lat" value="{{ request('lat') }}">
                        <input type="hidden" name="lng" value="{{ request('lng') }}">
                    @endif


                    <div>
                        <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
                        <select name="city" id="city"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                            <option value="">Semua Kota</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                            <option value="">Semua</option>
                            <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline
                            </option>
                        </select>
                    </div>

                    {{-- Ganti input range harga dengan tombol dropdown/modal trigger --}}
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rentang Harga</label>
                        <input type="hidden" name="price_min" id="price_min_hidden" value="{{ request('price_min') }}">
                        <input type="hidden" name="price_max" id="price_max_hidden" value="{{ request('price_max') }}">

                        <button type="button" id="open-price-modal"
                            class="w-full text-left bg-white border border-gray-300 rounded-lg px-4 py-2 font-semibold flex justify-between items-center hover:bg-gray-50 transition duration-150 ease-in-out">
                            <span id="price-display">
                                @if (request('price_min') || request('price_max'))
                                    Rp {{ number_format(request('price_min') ?: 0) }} - Rp
                                    {{ number_format(request('price_max') ?: 'Max') }}
                                @else
                                    Semua Harga
                                @endif
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>


                    <div class="md:col-span-2 lg:col-span-1 flex items-end justify-between md:justify-end gap-6">

                        <div class="flex items-center">
                            <input type="checkbox" name="discount" value="1" id="discount"
                                class="form-checkbox h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                {{ request('discount') == '1' ? 'checked' : '' }}>
                            <label for="discount" class="ml-2 text-sm font-semibold text-gray-700">Hanya Diskon</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="rating_filter" value="1" id="rating_filter"
                                class="form-checkbox h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                {{ request('rating_filter') == '1' ? 'checked' : '' }}>
                            <label for="rating_filter" class="ml-2 text-sm font-semibold text-gray-700">Rating ≥ 4</label>
                        </div>
                    </div>

                </div>

                <div class="mt-8 pt-4 border-t flex justify-end">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-150 font-bold">
                        Reset Filter
                    </a>
                    <button type="submit"
                        class="ml-3 bg-blue-600 text-white font-bold px-8 py-2 rounded-lg hover:bg-blue-700 transition duration-150 shadow-lg transform hover:scale-[1.02]">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        @endif

        {{-- Pastikan logic highlight service berada di luar blok form --}}
        @php
            $highlightServices = $services->filter(
                fn($s) => $s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until),
            );
        @endphp

        @if ($highlightServices->count() > 0)
            <section class="mb-12">
                <h2 class="text-2xl font-extrabold mb-5 text-gray-900">Layanan Unggulan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($highlightServices as $service)
                        @php
                            $images = json_decode($service->images, true);
                            $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                            $profilePhoto =
                                $service->user && $service->user->profile_photo
                                    ? asset('storage/' . $service->user->profile_photo)
                                    : asset('images/profile-user.png');
                            $userFavorites = auth()->user()->favoriteServices ?? collect();
                            $isFavorited = $userFavorites->contains($service->id);
                        @endphp
                        <div
                            class="bg-accent/20 rounded-lg overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 border-2 border-accent relative shadow-xl">
                            @auth
                                <form action="{{ route('services.toggleFavorite', $service->slug) }}" method="POST"
                                    class="absolute top-2 right-2 z-10">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="focus:outline-none bg-white p-1 rounded-full shadow-md">
                                        @if ($isFavorited)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @endauth

                            <div class="relative bg-white">
                                <a href="{{ route('services.show', $service->slug) }}">
                                    @if ($mainImage)
                                        <img src="{{ $mainImage }}" alt="{{ $service->title }}"
                                            class="w-full h-40 object-cover">
                                    @else
                                        <div
                                            class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                                            Tidak Ada Gambar</div>
                                    @endif
                                </a>
                                <span
                                    class="absolute top-2 left-2 bg-accent text-gray-900 text-xs font-extrabold px-3 py-1 rounded-full">UNGGULAN</span>
                            </div>
                            <div class="p-4">
                                <a href="{{ route('services.show', $service->slug) }}"
                                    class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>
                                @if ($service->discount_price && $service->discount_price > 0)
                                    <p class="text-lg font-bold text-red-600 mb-1">
                                        Rp {{ number_format($service->discount_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 line-through mb-3">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-lg font-bold text-green-600 mb-3">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </p>
                                @endif

                                {{-- MODIFIKASI: Menggunakan line-clamp-2 dan batas 8 kata --}}
                                <p class="text-gray-500 mb-4 line-clamp-2">
                                    {{ Str::words(strip_tags($service->description), 8, '...') }}
                                </p>

                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    @if ($profilePhoto)
                                        <img src="{{ $profilePhoto }}"
                                            alt="{{ $service->user->full_name ?? 'N/A' }}"
                                            class="w-7 h-7 rounded-full object-cover">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-gray-300"></div>
                                    @endif
                                    <span class="font-semibold">{{ $service->user->full_name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- START: Layanan Paling Populer (TAMBAHAN) --}}
        @if ($popularServices->count() > 0)
            <section class="mb-12">
                <h2 class="text-2xl font-extrabold mb-5 text-gray-900">Layanan Paling Populer</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($popularServices as $service)
                        @php
                            $images = json_decode($service->images, true);
                            $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                            $profilePhoto =
                                $service->user && $service->user->profile_photo
                                    ? asset('storage/' . $service->user->profile_photo)
                                    : asset('images/profile-user.png');
                            $userFavorites = auth()->user()->favoriteServices ?? collect();
                            $isFavorited = $userFavorites->contains($service->id);
                        @endphp
                        <div
                            class="bg-white rounded-lg border border-gray-200 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 relative shadow-lg">
                            {{-- Card Konten (Sama seperti service card normal) --}}
                            @auth
                                <form action="{{ route('services.toggleFavorite', $service->slug) }}" method="POST"
                                    class="absolute top-2 right-2 z-10">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="focus:outline-none bg-white p-1 rounded-full shadow-md">
                                        @if ($isFavorited)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @endauth

                            <a href="{{ route('services.show', $service->slug) }}">
                                @if ($mainImage)
                                    <img src="{{ $mainImage }}" alt="{{ $service->title }}"
                                        class="w-full h-40 object-cover">
                                @else
                                    <div
                                        class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                                        Tidak Ada Gambar</div>
                                @endif
                            </a>
                            <div class="p-4">
                                <a href="{{ route('services.show', $service->slug) }}"
                                    class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>
                                @if ($service->discount_price && $service->discount_price > 0)
                                    <p class="text-lg font-bold text-red-600 mb-1">
                                        Rp {{ number_format($service->discount_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 line-through mb-3">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-lg font-bold text-green-600 mb-3">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </p>
                                @endif

                                <p class="text-gray-500 mb-4 line-clamp-2">
                                    {{ Str::words(strip_tags($service->description), 8, '...') }}
                                </p>

                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    @if ($profilePhoto)
                                        <img src="{{ $profilePhoto }}"
                                            alt="{{ $service->user->full_name ?? 'N/A' }}"
                                            class="w-7 h-7 rounded-full object-cover">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-gray-300"></div>
                                    @endif
                                    <span class="font-semibold">{{ $service->user->full_name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm font-bold text-yellow-500">
                                        ({{ number_format($service->avg_rating, 1) }})
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
        {{-- END: Layanan Paling Populer --}}


        <section>
            <h2 class="text-2xl font-extrabold mb-5 text-gray-900">Semua Layanan</h2>
            <div id="normal-services-grid"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                {{-- Gunakan variabel $normalServices yang sudah di filter di blok PHP atas --}}
                @forelse($normalServices as $service)
                    @php
                        $images = json_decode($service->images, true);
                        $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                        $profilePhoto =
                            $service->user && $service->user->profile_photo
                                ? asset('storage/' . $service->user->profile_photo)
                                : asset('images/profile-user.png');
                        $userFavorites = auth()->user()->favoriteServices ?? collect();
                        $isFavorited = $userFavorites->contains($service->id);
                    @endphp
                    <div
                        class="normal-service-card bg-white rounded-lg border border-gray-200 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 relative">
                        @auth
                            <form action="{{ route('services.toggleFavorite', $service->slug) }}" method="POST"
                                class="absolute top-2 right-2 z-10">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="focus:outline-none bg-white p-1 rounded-full shadow-md">
                                    @if ($isFavorited)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        @endauth
                        <a href="{{ route('services.show', $service->slug) }}">
                            @if ($mainImage)
                                <img src="{{ $mainImage }}" alt="{{ $service->title }}"
                                    class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                                    Tidak Ada Gambar</div>
                            @endif
                        </a>
                        <div class="p-4">
                            <a href="{{ route('services.show', $service->slug) }}"
                                class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>
                            @if ($service->discount_price && $service->discount_price > 0)
                                <p class="text-lg font-bold text-red-600 mb-1">
                                    Rp {{ number_format($service->discount_price, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 line-through mb-3">
                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                </p>
                            @else
                                <p class="text-lg font-bold text-green-600 mb-3">
                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                </p>
                            @endif

                            {{-- MODIFIKASI: Menggunakan line-clamp-2 dan batas 8 kata --}}
                            <p class="text-gray-500 mb-4 line-clamp-2">
                                {{ Str::words(strip_tags($service->description), 8, '...') }}
                            </p>
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                @if ($profilePhoto)
                                    <img src="{{ $profilePhoto }}" alt="{{ $service->user->full_name ?? 'N/A' }}"
                                        class="w-7 h-7 rounded-full object-cover">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-gray-300"></div>
                                @endif
                                <span class="font-semibold">{{ $service->user->full_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                {{-- START: Perbaikan Icon Bintang --}}
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                @endfor
                                {{-- END: Perbaikan Icon Bintang --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-10 font-bold">Tidak ada layanan yang tersedia saat ini.
                    </p>
                @endforelse
            </div>
            <div id="loading-indicator"
                class="w-full flex justify-center items-center py-8 gap-3 text-gray-600 hidden">
                <div class="loader"></div>
                <span class="font-bold">Memuat layanan lainnya...</span>
            </div>
        </section>

    </div>

    {{-- Script CDN Swiper (Dari welcome.blade.php) --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // START: Inisialisasi Swiper
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                // Targetkan ID kustom yang baru
                nextEl: "#swiper-button-next-custom",
                prevEl: "#swiper-button-prev-custom",
            },
            // Responsiveness: Tampilkan lebih dari 1 slide di layar lebar
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 1,
                    spaceBetween: 40,
                },
            }
        });
        // END: Inisialisasi Swiper

        document.addEventListener('DOMContentLoaded', () => {
            const openLocationModalBtn = document.getElementById('open-location-modal');
            const locationSearchModal = document.getElementById('location-search-modal');

            // Tambahkan elemen close 'X' dan 'Batal' di modal lokasi
            const locationModalCloseXBtn = document.getElementById('location-modal-close-x');
            const locationModalCloseButton = document.getElementById('location-modal-close-button');


            // Elemen Modal Lokasi
            const locationFormModal = document.getElementById('location-form-modal');
            const addressInputModal = document.getElementById('address-input-modal');
            const latInputModal = document.getElementById('lat-modal');
            const lngInputModal = document.getElementById('lng-modal');
            const resultsListModal = document.getElementById('autocomplete-results-modal');

            // Elemen Search dan Filter
            const mainSearchInput = document.getElementById('main-search-input');
            const secondarySearchHidden = document.getElementById('secondary-search-hidden');

            // Elemen Pencarian Cepat
            const quickSearchRecommendations = document.getElementById('quick-search-recommendations');


            // Elemen Filter Lain
            const notificationModal = document.getElementById('notification-modal');
            const modalMessage = document.getElementById('modal-message');
            const modalCloseBtn = document.getElementById('modal-close-btn');

            const priceModal = document.getElementById('price-modal');
            const openPriceModalBtn = document.getElementById('open-price-modal');
            const priceModalForm = document.getElementById('price-form-modal');
            const priceModalResetBtn = document.getElementById('price-modal-reset');
            const priceMinModalInput = document.getElementById('price_min_modal');
            const priceMaxModalInput = document.getElementById('price_max_modal');
            const priceMinHiddenInput = document.getElementById('price_min_hidden');
            const priceMaxHiddenInput = document.getElementById('price_max_hidden');
            const priceDisplay = document.getElementById('price-display');
            const secondaryFilterForm = document.getElementById('secondary-filter-form');

            let geocodeTimeout = null;
            let isLoading = false;

            const normalServiceCards = document.querySelectorAll('.normal-service-card');
            const loadingIndicator = document.getElementById('loading-indicator');
            const cardsPerLoad = 8;
            let currentlyDisplayed = cardsPerLoad;


            // LOGIKA TAMBAHAN: Sinkronisasi Search Input dan Hidden Field Filter
            if (mainSearchInput && secondarySearchHidden) {
                mainSearchInput.addEventListener('input', () => {
                    secondarySearchHidden.value = mainSearchInput.value;
                });
            }

            // LOGIKA PENCARIAN CEPAT
            if (mainSearchInput && quickSearchRecommendations) {
                mainSearchInput.addEventListener('focus', () => {
                    quickSearchRecommendations.classList.remove('hidden');
                });

                // Hapus event listener blur yang lama, diganti dengan click handler global yang lebih baik
                // mainSearchInput.addEventListener('blur', () => { ... });
            }

            // LOGIKA PENCARIAN CEPAT: Auto-Close di luar area
            document.addEventListener('click', (e) => {
                const isClickInsideSearchBox = mainSearchInput.contains(e.target);
                const isClickInsideRecommendations = quickSearchRecommendations.contains(e.target);

                if (!isClickInsideSearchBox && !isClickInsideRecommendations) {
                    quickSearchRecommendations.classList.add('hidden');
                }
            });


            // Fungsi untuk menampilkan modal (umum)
            function showModal(message) {
                modalMessage.textContent = message;
                notificationModal.classList.remove('hidden');
            }

            // Fungsi untuk menyembunyikan modal (umum)
            function hideModal(modalElement) {
                modalElement.classList.add('hidden');
            }

            modalCloseBtn.addEventListener('click', () => hideModal(notificationModal));
            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) {
                    hideModal(notificationModal);
                }
            });

            // LOGIKA UNTUK LOCATION SEARCH MODAL
            const hideLocationModal = () => hideModal(locationSearchModal);

            openLocationModalBtn.addEventListener('click', () => {
                locationSearchModal.classList.remove('hidden');
            });

            // Tambahkan event listener untuk tombol close 'X' dan 'Batal'
            if (locationModalCloseXBtn) {
                 locationModalCloseXBtn.addEventListener('click', hideLocationModal);
            }
            if (locationModalCloseButton) {
                 locationModalCloseButton.addEventListener('click', hideLocationModal);
            }

            locationSearchModal.addEventListener('click', (e) => {
                // Tutup modal jika mengklik backdrop
                if (e.target === locationSearchModal) {
                    hideLocationModal();
                }
            });

            // Autocomplete alamat untuk modal lokasi
            addressInputModal.addEventListener('input', () => {
                clearTimeout(geocodeTimeout);
                const query = addressInputModal.value.trim();
                if (query.length < 3) {
                    resultsListModal.style.display = 'none';
                    return;
                }
                geocodeTimeout = setTimeout(() => {
                    fetch(
                            `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1`
                        )
                        .then(res => res.json())
                        .then(data => {
                            resultsListModal.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const li = document.createElement('li');
                                    li.textContent = item.display_name;
                                    li.className = 'p-3 cursor-pointer';
                                    li.addEventListener('click', () => {
                                        addressInputModal.value = item.display_name;
                                        latInputModal.value = item.lat;
                                        lngInputModal.value = item.lon;
                                        resultsListModal.style.display = 'none';
                                        // Pastikan rekomendasi cepat tersembunyi
                                        quickSearchRecommendations.classList.add('hidden');
                                    });
                                    resultsListModal.appendChild(li);
                                });
                                resultsListModal.style.display = 'block';
                            } else {
                                resultsListModal.style.display = 'none';
                            }
                        })
                        .catch(() => {
                            showModal(
                                'Tidak dapat mengambil saran alamat. Silakan periksa koneksi Anda.'
                            );
                        });
                }, 500);
            });

            // Sembunyikan hasil autocomplete jika mengklik di luar input/modal
            document.addEventListener('click', (e) => {
                const isClickInsideModal = locationSearchModal.contains(e.target);
                const isClickOnInputOrResults = addressInputModal.contains(e.target) || resultsListModal.contains(e.target);

                // Ini menangani penutupan hasil autocomplete (dropdown)
                if (locationSearchModal.classList.contains('hidden') || (isClickInsideModal && !isClickOnInputOrResults)) {
                    resultsListModal.style.display = 'none';
                }
            });

            locationFormModal.addEventListener('submit', (e) => {
                if (!latInputModal.value || !lngInputModal.value) {
                    e.preventDefault();
                    showModal(
                        'Silakan pilih alamat yang valid dari daftar dropdown untuk mengisi koordinat.');
                }
                // Jika valid, form akan dikirim ke route services.nearby
            });
            // END LOGIKA UNTUK LOCATION SEARCH MODAL


            // LOGIKA UNTUK PRICE FILTER MODAL (Sama seperti sebelumnya)
            openPriceModalBtn.addEventListener('click', () => {
                priceMinModalInput.value = priceMinHiddenInput.value;
                priceMaxModalInput.value = priceMaxHiddenInput.value;
                priceModal.classList.remove('hidden');
            });

            priceModal.addEventListener('click', (e) => {
                if (e.target === priceModal) {
                    hideModal(priceModal);
                }
            });

            priceModalResetBtn.addEventListener('click', () => {
                priceMinModalInput.value = '';
                priceMaxModalInput.value = '';
                priceMinHiddenInput.value = '';
                priceMaxHiddenInput.value = '';
                priceDisplay.textContent = 'Semua Harga';
                hideModal(priceModal);

                // Hapus parameter harga, dan kirim form filter lanjutan
                const url = new URL(window.location.href);
                url.searchParams.delete('price_min');
                url.searchParams.delete('price_max');

                secondaryFilterForm.querySelector('#price_min_hidden').value = '';
                secondaryFilterForm.querySelector('#price_max_hidden').value = '';

                secondaryFilterForm.submit();
            });

            priceModalForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const minPrice = priceMinModalInput.value;
                const maxPrice = priceMaxModalInput.value;

                if (minPrice && maxPrice && parseInt(minPrice) > parseInt(maxPrice)) {
                    showModal('Harga Minimum tidak boleh lebih besar dari Harga Maksimum.');
                    return;
                }

                priceMinHiddenInput.value = minPrice;
                priceMaxHiddenInput.value = maxPrice;

                let displayText = 'Semua Harga';
                if (minPrice || maxPrice) {
                    const minFormatted = minPrice ? 'Rp ' + parseInt(minPrice).toLocaleString('id-ID') : 'Min';
                    const maxFormatted = maxPrice ? 'Rp ' + parseInt(maxPrice).toLocaleString('id-ID') : 'Max';
                    displayText = `${minFormatted} - ${maxFormatted}`;
                }
                displayText = displayText.replace('Rp Max', 'Max').replace('Min - Rp', 'Min - ');
                priceDisplay.textContent = displayText;

                hideModal(priceModal);
                secondaryFilterForm.submit();
            });
            // END LOGIKA UNTUK PRICE FILTER MODAL


            // Infinite Scroll Logic (Tidak berubah)
            if (normalServiceCards.length > cardsPerLoad) {
                normalServiceCards.forEach((card, index) => {
                    if (index >= cardsPerLoad) {
                        card.classList.add('hidden');
                    }
                });

                const loadMoreCards = () => {
                    if (isLoading) return;
                    isLoading = true;
                    loadingIndicator.classList.remove('hidden');

                    setTimeout(() => {
                        const nextLimit = currentlyDisplayed + cardsPerLoad;
                        for (let i = currentlyDisplayed; i < nextLimit; i++) {
                            if (normalServiceCards[i]) {
                                normalServiceCards[i].classList.remove('hidden');
                            }
                        }
                        currentlyDisplayed = nextLimit;
                        loadingIndicator.classList.add('hidden');
                        isLoading = false;

                        if (currentlyDisplayed >= normalServiceCards.length) {
                            loadingIndicator.remove();
                            window.removeEventListener('scroll', handleScroll);
                        }
                    }, 1000); // Penundaan 1 detik
                };

                const handleScroll = () => {
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
                        loadMoreCards();
                    }
                };

                window.addEventListener('scroll', handleScroll);
            } else {
                loadingIndicator.remove();
            }
        });
    </script>
</x-app-layout>
