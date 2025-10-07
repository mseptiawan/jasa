<x-app-layout>
    {{-- Tambahkan Style dari Dashboard untuk konsistensi --}}
    <style>
        .text-primary { color: #2b3cd7; }
        .bg-primary { background-color: #2b3cd7; }
        .bg-accent { background-color: #ffd231; }
        .text-accent { color: #ffd231; }
        .text-red-500 { color: #ef4444; }
        .text-green-600 { color: #16a34a; }
        /* Pastikan tidak ada box-shadow pada elemen utama */
        .no-shadow { box-shadow: none !important; }
        /* Tambahkan style untuk mendukung desain Ringkasan Pesanan yang baru */
        .bg-primary\/5 { background-color: rgba(43, 60, 215, 0.05); }
        .border-primary\/20 { border-color: rgba(43, 60, 215, 0.2); }
    </style>

    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        <h1 class="text-3xl font-black text-gray-900 mb-8 border-b pb-4">🛒 Keranjang Saya</h1>

        @if ($carts->isEmpty())
            <div class="bg-white p-10 rounded-xl border border-gray-200 text-center flex flex-col items-center justify-center">
                {{-- Placeholder Gambar Sticker image2.png --}}
                <img src="{{ asset('images/image2.png') }}" alt="Keranjang Kosong"
                    class="w-48 h-auto mb-6 object-contain">

                <p class="text-xl text-gray-700 font-semibold mb-4">Keranjang Anda kosong.</p>
                <p class="text-gray-500 mb-6">Ayo temukan layanan terbaik untuk mulai proyek Anda!</p>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center bg-primary text-accent font-bold py-3 px-8 rounded-lg hover:opacity-90 transition-opacity no-shadow">
                    Cari Layanan Sekarang
                </a>
            </div>
        @else
            {{-- START: Grid/List Keranjang --}}
            <div class="space-y-6 mb-8">
                @php $totalPrice = 0; @endphp
                @foreach ($carts as $cart)
                    @php
                        $price = $cart->service->price;
                        $finalPrice = $cart->service->discount_price > 0 ? $cart->service->discount_price : $price;
                        $totalPrice += $finalPrice;
                        $images = json_decode($cart->service->images, true);
                        $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                    @endphp

                    <div class="flex flex-col md:flex-row bg-white rounded-xl p-4 border border-gray-200 transition-colors duration-300 hover:border-primary no-shadow">

                        {{-- GAMBAR (15%) --}}
                        <div class="flex-shrink-0 w-full md:w-32 h-24 rounded-lg overflow-hidden mb-4 md:mb-0 md:mr-4 border border-gray-100">
                            <a href="{{ route('services.show', $cart->service->slug) }}" class="block h-full">
                                @if ($mainImage)
                                    <img src="{{ $mainImage }}" alt="{{ $cart->service->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-semibold">
                                        No Image
                                    </div>
                                @endif
                            </a>
                        </div>

                        {{-- DETAIL (55%) --}}
                        <div class="flex-grow md:pr-4">
                            <a href="{{ route('services.show', $cart->service->slug) }}"
                                class="text-lg font-extrabold text-gray-900 hover:text-primary transition-colors line-clamp-2 mb-1">
                                {{ $cart->service->title }}
                            </a>
                            <p class="text-sm text-gray-500 mb-3">Oleh: <span class="font-semibold">{{ $cart->service->user->full_name ?? 'N/A' }}</span></p>

                            {{-- Harga --}}
                            <div class="mt-2">
                                @if ($cart->service->discount_price && $cart->service->discount_price > 0)
                                    <p class="text-sm text-red-600 font-bold mb-1">
                                        Harga Diskon: Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500 line-through">
                                        Harga Normal: Rp {{ number_format($price, 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-md font-bold text-green-600">
                                        Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- AKSI (30%) --}}
                        <div class="flex-shrink-0 flex flex-col space-y-2 mt-4 md:mt-0 md:w-40">

                            {{-- Tombol Pesan/Checkout --}}
                            <a href="{{ route('orders.create', ['service' => $cart->service->slug]) }}"
                                class="w-full text-center bg-primary text-accent font-bold py-2 px-4 rounded-lg hover:opacity-90 transition-opacity flex items-center justify-center text-sm no-shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                                Pesan Sekarang
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('cart.remove', $cart->service_id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full text-center text-sm font-semibold text-red-500 bg-red-50 hover:bg-red-100 py-2 px-4 rounded-lg transition-colors border border-red-200 flex items-center justify-center no-shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- END: Grid/List Keranjang --}}

            {{-- START: Ringkasan Total dan Aksi Massal (Improved Design) --}}
            <div class="bg-primary/5 p-6 rounded-xl border border-primary/20 no-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-3">Ringkasan Pesanan</h3>

                {{-- Menampilkan Total Harga dengan penekanan --}}
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-700 font-semibold text-lg">Total Harga ({{ $carts->count() }} Jasa):</span>
                    <div class="text-right">
                        {{-- Menonjolkan total harga dengan ukuran besar dan warna primary --}}
                        <span class="text-4xl font-extrabold text-primary">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Belum termasuk biaya layanan/transaksi.</p>
                    </div>
                </div>

                {{-- Aksi Massal (Hanya Kosongkan Keranjang) --}}
                <div class="flex flex-col sm:flex-row justify-end">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto bg-red-500 text-white font-bold px-6 py-3 rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center no-shadow text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" /></svg>
                            Kosongkan Keranjang
                        </button>
                    </form>
                    {{-- Tombol "Lanjutkan Pembayaran (WIP)" telah dihapus --}}
                </div>
            </div>
            {{-- END: Ringkasan Total dan Aksi Massal (Improved Design) --}}
        @endif
    </div>
</x-app-layout>
