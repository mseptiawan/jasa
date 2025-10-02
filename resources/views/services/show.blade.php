<x-app-layout>
    {{-- CSS Kustom --}}
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        .bg-primary {
            background-color: #000000;
        }

        .text-primary {
            color: #000000;
        }

        .border-primary {
            border-color: #000000;
        }

        .bg-accent {
            background-color: #e0e0e0;
        }

        .text-accent {
            color: #555555;
        }

        .text-green-600 {
            color: #16a34a;
        }

        .text-yellow-400 {
            color: #fbbf24;
        }

        .text-red-500 {
            color: #ef4444;
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
    </style>

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
            {{-- Kolom Kiri: Gambar Layanan --}}
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
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/70 backdrop-blur-sm p-3 rounded-full hover:bg-white transition-colors">
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
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/70 backdrop-blur-sm p-3 rounded-full hover:bg-white transition-colors">
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
                @if ($service->images && count(json_decode($service->images)) > 1)
                    <div class="flex justify-center mt-4 space-x-2">
                        @foreach (json_decode($service->images) as $index => $img)
                            <div class="w-3 h-3 rounded-full cursor-pointer {{ $index === 0 ? 'bg-primary' : 'bg-gray-300' }}"
                                 data-index="{{ $index }}"></div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Detail & Aksi --}}
            <div class="space-y-6">
                {{-- Judul dan Harga --}}
                <div>
                    <h1 class="text-3xl font-extrabold mb-1 text-gray-900 leading-tight">{{ $service->title }}</h1>
                    <p class="text-xl font-medium text-gray-700">Mulai dari <span class="font-bold text-green-600">Rp
                            {{ number_format($service->price, 0, ',', '.') }}</span></p>
                </div>

                {{-- Detail Tambahan (Garis miring) --}}
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-gray-700">
                        Kategori: <span class="font-normal">{{ $service->subcategory->category->name ?? '-' }}</span> /
                        Subkategori: <span class="font-normal">{{ $service->subcategory->name ?? '-' }}</span>
                    </p>
                </div>

                {{-- Deskripsi Layanan --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-gray-800">Deskripsi Layanan</h2>
                    <p class="text-gray-700 leading-relaxed">{!! nl2br(e($service->description)) !!}</p>
                </div>


                {{-- Bagian Jaminan --}}
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="font-bold mb-2">Jaminan Kualitas</p>
                    <p class="text-sm text-gray-700">Kami menjamin setiap layanan yang terdaftar telah diverifikasi.
                        Jika ada masalah, hubungi Layanan Pelanggan kami untuk bantuan.</p>
                </div>

                {{-- Detail Spesifikasi --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-gray-800">Spesifikasi</h2>
                    <ul class="text-gray-700 space-y-2 text-sm">
                        <li class="flex items-center gap-2"><span class="font-semibold">Jenis Pekerjaan:</span>
                            {{ $service->job_type ?? '-' }}</li>
                        <li class="flex items-center gap-2"><span class="font-semibold">Pengalaman:</span>
                            {{ $service->experience ?? '-' }}</li>
                        <li class="flex items-center gap-2"><span class="font-semibold">Alamat:</span>
                            {{ $service->address ?? '-' }}</li>
                    </ul>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 space-y-4">
                    @if (auth()->check() && auth()->id() !== $service->user_id)
                        <!-- Tombol untuk user yang login dan bukan pemilik service -->
                        <a href="{{ route('orders.create', ['service' => $service->slug]) }}"
                           class="w-full flex items-center justify-center gap-2 bg-black text-white font-bold py-3 px-4 rounded-lg hover:opacity-80 transition-opacity duration-300 text-sm">
                            <!-- Icon keranjang -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="currentColor"
                                 viewBox="0 0 640 640">
                                <path
                                      d="M24 48C10.7 48 0 58.7 0 72C0 85.3 10.7 96 24 96L69.3 96C73.2 96 76.5 98.8 77.2 102.6L129.3 388.9C135.5 423.1 165.3 448 200.1 448L456 448C469.3 448 480 437.3 480 424C480 410.7 469.3 400 456 400L200.1 400C188.5 400 178.6 391.7 176.5 380.3L171.4 352L475 352C505.8 352 532.2 330.1 537.9 299.8L568.9 133.9C572.6 114.2 557.5 96 537.4 96L124.7 96L124.3 94C119.5 67.4 96.3 48 69.2 48L24 48zM208 576C234.5 576 256 554.5 256 528C256 501.5 234.5 480 208 480C181.5 480 160 501.5 160 528C160 554.5 181.5 576 208 576zM432 576C458.5 576 480 554.5 480 528C480 501.5 458.5 480 432 480C405.5 480 384 501.5 384 528C384 554.5 405.5 576 432 576z" />
                            </svg>
                            Pesan Sekarang
                        </a>

                        <a href="{{ route('conversations.start') }}"
                           onclick="event.preventDefault(); document.getElementById('start-chat-{{ $service->id }}').submit();"
                           class="w-full flex items-center justify-center gap-2 bg-white border border-gray-400 text-gray-700 font-bold py-3 px-4 rounded-lg hover:bg-gray-100 transition-colors duration-300 text-sm">
                            <!-- Icon chat -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
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
                        <!-- Kalau user non-auth, arahkan ke login -->
                        <a href="{{ route('login') }}"
                           class="w-full flex items-center justify-center gap-2 bg-black text-white font-bold py-3 px-4 rounded-lg hover:opacity-80 transition-opacity duration-300 text-sm">
                            Pesan Sekarang
                        </a>

                        <a href="{{ route('login') }}"
                           class="w-full flex items-center justify-center gap-2 bg-white border border-gray-400 text-gray-700 font-bold py-3 px-4 rounded-lg hover:bg-gray-100 transition-colors duration-300 text-sm">
                            Hubungi Penjual
                        </a>
                    @endif
                </div>


            </div>
        </div>

        {{-- Ulasan, Profil Penyedia Jasa, dan Jasa Lainnya (di luar box utama) --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Ulasan Pelanggan di kolom kiri --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-xl border border-gray-200">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">Ulasan Pelanggan</h2>
                    @if ($service->reviews && $service->reviews->count() > 0)
                        @php
                            $avgRating = $service->reviews->avg('rating');
                            $reviewCount = $service->reviews->count();
                        @endphp
                        <div class="flex items-center mb-4">
                            <span class="text-4xl font-extrabold mr-2">{{ number_format($avgRating, 1) }}</span>
                            <div class="flex items-center text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                         fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path
                                              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-gray-500 ml-3">({{ $reviewCount }} ulasan)</span>
                        </div>
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2 scrollbar-hide">
                            @foreach ($service->reviews as $review)
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center text-sm font-medium">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                     fill="currentColor"
                                                     viewBox="0 0 20 20">
                                                    <path
                                                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <p class="text-gray-800 text-base mb-2 font-medium">
                                        {{ $review->comment ?? 'Tidak ada komentar.' }}</p>
                                    <p class="text-sm text-gray-500">Oleh: <span
                                              class="font-semibold">{{ $review->user->full_name ?? 'Pengguna' }}</span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Belum ada ulasan untuk layanan ini. Jadilah yang pertama!</p>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Penyedia Jasa --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Identitas Penyedia Jasa --}}
                <div class="bg-white p-6 rounded-xl border border-gray-200 text-center">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">Penyedia Jasa</h2>
                    <img src="{{ $service->user && $service->user->profile_photo ? asset('storage/' . $service->user->profile_photo) : asset('images/profile-user.png') }}"
                         alt="{{ $service->user->full_name ?? 'N/A' }}"
                         class="w-24 h-24 object-cover rounded-full border-2 border-accent mx-auto mb-4" />
                    <p class="font-extrabold text-2xl text-gray-900 leading-snug">
                        {{ $service->user->full_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500 truncate mb-4">{{ $service->user->email ?? 'N/A' }}</p>

                    <p class="text-sm text-gray-700 italic mb-4">"{{ $service->user->bio ?? 'Bio belum tersedia.' }}"
                    </p>

                    <h3 class="font-bold text-gray-800 mb-2">Kontak & Jaringan</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        @if ($service->user->website)
                            <a href="{{ $service->user->website }}"
                               target="_blank"
                               class="flex items-center justify-start text-primary hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4 text-gray-400"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101" />
                                </svg>
                                <span class="ml-2">Website</span>
                            </a>
                        @endif
                        @if ($service->user->linkedin)
                            <a href="{{ $service->user->linkedin }}"
                               target="_blank"
                               class="flex items-center justify-start text-primary hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4 text-gray-400"
                                     viewBox="0 0 24 24"
                                     fill="currentColor">
                                    <path
                                          d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.761s.784-1.76 1.75-1.76 1.75.79 1.75 1.76-.783 1.761-1.75 1.761zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                </svg>
                                <span class="ml-2">LinkedIn</span>
                            </a>
                        @endif
                        @if ($service->user->instagram)
                            <a href="{{ $service->user->instagram }}"
                               target="_blank"
                               class="flex items-center justify-start text-primary hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24"
                                     fill="currentColor"
                                     class="h-4 w-4 text-gray-400">
                                    <path
                                          d="M7.75 2A5.75 5.75 0 002 7.75v8.5A5.75 5.75 0 007.75 22h8.5A5.75 5.75 0 0022 16.25v-8.5A5.75 5.75 0 0016.25 2h-8.5zm10 2a3.75 3.75 0 013.75 3.75v8.5A3.75 3.75 0 0117.75 20h-8.5A3.75 3.75 0 015.5 16.25v-8.5A3.75 3.75 0 019.25 4h8.5zM12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm5.25-2a1 1 0 100 2 1 1 0 000-2z" />
                                </svg>
                                <span class="ml-2">Instagram</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Jasa Lainnya --}}
        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Jasa Lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
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
                       class="bg-white rounded-lg border border-gray-200 overflow-hidden block">
                        <div class="relative">
                            @if ($mainImage)
                                <img src="{{ $mainImage }}"
                                     alt="{{ $otherService->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                    Tidak Ada Gambar</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-lg mb-1 text-gray-900 truncate">{{ $otherService->title }}</p>
                            <p class="text-md font-bold text-green-600 mb-2">Rp
                                {{ number_format($otherService->price, 0, ',', '.') }}</p>

                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <img src="{{ $profilePhoto }}"
                                     alt="{{ $otherService->user->full_name ?? 'N/A' }}"
                                     class="w-7 h-7 rounded-full object-cover">
                                <span
                                      class="text-gray-700 font-medium">{{ $otherService->user->full_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-4">Tidak ada jasa lain untuk ditampilkan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Script untuk Navigasi Gambar --}}
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
</x-app-layout>
