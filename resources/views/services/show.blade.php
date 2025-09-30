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
                    <p class="text-gray-700 leading-relaxed">{{ $service->description }}</p>
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
                        <li class="flex items-center gap-2 "><span class="font-semibold">Alamat:</span>
                            {{ $service->address ?? '-' }}</li>
                    </ul>
                </div>

                {{-- Tombol Aksi --}}
                <a href="{{ route('services.edit', $service->slug) }}"
                   class="text-gray-500 hover:text-gray-700 mt-4 transition-colors duration-200"
                   title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
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
                                        <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
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
                                     class="h-4 w-4 text-gray-400"
                                     viewBox="0 0 24 24"
                                     fill="currentColor">
                                    <path
                                          d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.266.069 1.646.069 4.854 0 3.204-.012 3.584-.07 4.85-.148 3.252-1.691-4.771 4.919-4.919-.058-1.266-.069-1.646-.069-4.854 0-3.204.012-3.584.07-4.85.148-3.252 1.691-4.771 4.919-4.919 1.266-.058 1.646-.069 4.854-.069zm0-2.163c-3.259 0-3.667.014-4.945.072-4.358.201-6.78 2.623-6.981 6.981-.058 1.278-.072 1.686-.072 4.945 0 3.259.014 3.668.072 4.945.201 4.357 2.623 6.78 6.981 6.981 1.277.058 1.686.072 4.945.072 3.259 0 3.668-.014 4.945-.072 4.354-.201 6.782-2.623 6.981-6.981.058-1.277.072-1.686.072-4.945 0-3.259-.014-3.667-.072-4.945-.199-4.358-2.622-6.78-6.981-6.981-1.279-.058-1.687-.072-4.945-.072zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163c3.403 0 6.162-2.76 6.162-6.163 0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.79 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
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
