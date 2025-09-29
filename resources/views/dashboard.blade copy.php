<x-app-layout>
    {{-- This layout assumes the main app layout provides the header and a global modal function. --}}

    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        <main>
            <div class="bg-white p-6 rounded-lg border border-gray-200 mb-8">
                <div class="flex flex-wrap justify-between items-center gap-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Jelajahi Layanan</h2>
                    <form action="{{ route('dashboard') }}" method="GET" class="relative w-full sm:w-auto sm:max-w-xs">
                        <input type="text" name="search" placeholder="Cari layanan..." value="{{ request('search') }}" class="w-full py-2 pl-4 pr-10 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-primary focus:border-transparent transition" autocomplete="off">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                @php
                    $categories = \App\Models\Category::all();
                @endphp

                @if($categories->count() > 0)
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-3">Filter Berdasarkan Kategori</h3>
                    <div class="flex flex-wrap gap-3">
                        {{-- Semua --}}
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-colors
                            {{ !request('category') ? 'bg-black text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                            Semua
                        </a>

                        {{-- Per kategori --}}
                        @foreach ($categories as $cat)
                            <a href="{{ route('dashboard', ['category' => $cat->id]) }}"
                                class="px-4 py-2 rounded-full text-sm font-semibold transition-colors
                                {{ request('category') == $cat->id ? 'bg-black text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif


                @auth
                    @if (auth()->user()->role === 'admin')
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-md font-semibold text-gray-700 mb-3">Panel Admin</h3>
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('categories.index') }}" class="bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg hover:bg-gray-800 transition-colors">
                                    Kelola Kategori
                                </a>
                                <a href="{{ route('subcategories.index') }}" class="bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg hover:bg-gray-800 transition-colors">
                                    Kelola Subkategori
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Temukan Layanan Terbaik di Dekat Anda</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <p class="text-gray-600 mb-2">Dapatkan lokasi Anda secara instan.</p>
                        <button type="button" id="btn-nearby" class="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-4 rounded-lg shadow-md hover:opacity-90 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Cari Layanan Terdekat
                        </button>
                    </div>

                    <form id="location-form" action="{{ route('services.nearby') }}" method="get" class="flex flex-col">
                        <p class="text-gray-600 mb-2">Atau ketik alamat Anda secara manual.</p>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-grow">
                                <input type="text" id="address-input" placeholder="Ketik alamat Anda..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" autocomplete="off" required>
                                <input type="hidden" name="lat" id="lat">
                                <input type="hidden" name="lng" id="lng">
                                <ul id="autocomplete-results" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg max-h-60 overflow-y-auto z-10 list-none p-0 m-0 hidden"></ul>
                            </div>
                            <button type="submit" class="bg-primary text-white font-bold p-3 rounded-lg hover:opacity-90 transition-opacity">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @php
                $highlightServices = $services->filter(fn($s) => $s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until));
            @endphp
            @if ($highlightServices->count() > 0)
                <section class="mb-12">
                    <h2 class="text-2xl font-bold mb-5 text-gray-900">Layanan Unggulan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($highlightServices as $service)
                            @php
                                $images = json_decode($service->images, true);
                                $mainImage = !empty($images) ? asset('storage/' . $images[0]) : null;
                                $profilePhoto = $service->user?->profile_photo ? asset('storage/' . $service->user->profile_photo) : asset('images/profile-user.png');
                                $userFavorites = auth()->user()?->favoriteServices ?? collect();
                                $isFavorited = $userFavorites->contains($service->id);
                            @endphp
                            <div class="bg-white rounded-lg border border-2 border-accent overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 flex flex-col">
                                <div class="relative">
                                    <a href="{{ route('services.show', $service->slug) }}">
                                        @if ($mainImage)
                                            <img src="{{ $mainImage }}" alt="{{ $service->title }}" class="w-full h-40 object-cover">
                                        @else
                                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">Tidak Ada Gambar</div>
                                        @endif
                                    </a>
                                    <span class="absolute top-2 left-2 bg-accent text-gray-900 text-xs font-bold px-3 py-1 rounded-full">UNGGULAN</span>
                                    @auth
                                    <form action="{{ route('services.toggleFavorite', $service->slug) }}" method="POST" class="absolute top-2 right-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-8 h-8 rounded-full bg-white/70 backdrop-blur-sm flex items-center justify-center text-gray-600 hover:text-yellow-500 focus:outline-none transition-colors" aria-label="Toggle Favorite">
                                            @if ($isFavorited)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                            @endif
                                        </button>
                                    </form>
                                    @endauth
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <a href="{{ route('services.show', $service->slug) }}" class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                        <img src="{{ $profilePhoto }}" alt="{{ $service->user->full_name ?? 'N/A' }}" class="w-7 h-7 rounded-full object-cover">
                                        <span>{{ $service->user->full_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center mb-3">
                                        @if($service->avg_rating > 0)
                                            <div class="flex items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endfor
                                            </div>
                                            <span class="text-gray-600 text-sm ml-2">({{ number_format($service->avg_rating, 1) }})</span>
                                        @else
                                            <span class="text-gray-500 text-sm">Belum ada rating</span>
                                        @endif
                                    </div>
                                    <div class="mt-auto">
                                        <p class="text-lg font-semibold text-green-600">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @php
                $normalServices = $services->filter(fn($s) => !($s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until)));
            @endphp
            <section>
                <h2 class="text-2xl font-bold mb-5 text-gray-900">Semua Layanan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($normalServices as $service)
                        @php
                            $images = json_decode($service->images, true);
                            $mainImage = !empty($images) ? asset('storage/' . $images[0]) : null;
                            $profilePhoto = $service->user?->profile_photo ? asset('storage/' . $service->user->profile_photo) : asset('images/profile-user.png');
                            $userFavorites = auth()->user()?->favoriteServices ?? collect();
                            $isFavorited = $userFavorites->contains($service->id);
                        @endphp
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 flex flex-col">
                            <div class="relative">
                                <a href="{{ route('services.show', $service->slug) }}">
                                    @if ($mainImage)
                                        <img src="{{ $mainImage }}" alt="{{ $service->title }}" class="w-full h-40 object-cover">
                                    @else
                                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">Tidak Ada Gambar</div>
                                    @endif
                                </a>
                                @auth
                                <form action="{{ route('services.toggleFavorite', $service->slug) }}" method="POST" class="absolute top-2 right-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-white/70 backdrop-blur-sm flex items-center justify-center text-gray-600 hover:text-yellow-500 focus:outline-none transition-colors" aria-label="Toggle Favorite">
                                        @if ($isFavorited)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                        @endif
                                    </button>
                                </form>
                                @endauth
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <a href="{{ route('services.show', $service->slug) }}" class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    <img src="{{ $profilePhoto }}" alt="{{ $service->user->full_name ?? 'N/A' }}" class="w-7 h-7 rounded-full object-cover">
                                    <span>{{ $service->user->full_name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center mb-3">
                                    @if($service->avg_rating > 0)
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                        <span class="text-gray-600 text-sm ml-2">({{ number_format($service->avg_rating, 1) }})</span>
                                    @else
                                        <span class="text-gray-500 text-sm">Belum ada rating</span>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500 py-10">Tidak ada layanan yang cocok dengan pencarian Anda.</p>
                    @endforelse
                </div>
            </section>
        </main>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addressInput = document.getElementById('address-input');
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const resultsList = document.getElementById('autocomplete-results');
            const form = document.getElementById('location-form');
            let geocodeTimeout = null;

            function showCustomModal(message) {
                if(typeof window.showGlobalModal === 'function') {
                    window.showGlobalModal(message);
                } else {
                    alert(message);
                }
            }

            addressInput.addEventListener('input', () => {
                clearTimeout(geocodeTimeout);
                const query = addressInput.value.trim();
                if (query.length < 3) {
                    resultsList.style.display = 'none';
                    return;
                }
                geocodeTimeout = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1`)
                        .then(res => res.json())
                        .then(data => {
                            resultsList.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const li = document.createElement('li');
                                    li.textContent = item.display_name;
                                    li.className = 'p-3 cursor-pointer hover:bg-gray-100 transition-colors';
                                    li.addEventListener('click', () => {
                                        addressInput.value = item.display_name;
                                        latInput.value = item.lat;
                                        lngInput.value = item.lon;
                                        resultsList.style.display = 'none';
                                    });
                                    resultsList.appendChild(li);
                                });
                                resultsList.style.display = 'block';
                            } else {
                                resultsList.style.display = 'none';
                            }
                        }).catch(() => showCustomModal('Gagal mengambil saran alamat. Periksa koneksi Anda.'));
                }, 500);
            });

            document.addEventListener('click', (e) => {
                if (!resultsList.contains(e.target) && !addressInput.contains(e.target)) {
                    resultsList.style.display = 'none';
                }
            });

            form.addEventListener('submit', (e) => {
                if (!latInput.value || !lngInput.value) {
                    e.preventDefault();
                    showCustomModal('Silakan pilih alamat yang valid dari daftar dropdown untuk melanjutkan.');
                }
            });

            document.getElementById('btn-nearby').addEventListener('click', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;
                        form.submit();
                    }, () => showCustomModal('Tidak dapat mengambil lokasi Anda. Pastikan Anda telah memberikan izin lokasi.'));
                } else {
                    showCustomModal('Geolocation tidak didukung oleh browser ini.');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
