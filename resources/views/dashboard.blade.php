<x-app-layout>
    <!-- <h2>Dashboard</h2> -->
    @php
        $categories = \App\Models\Category::all();
    @endphp

    <div style="margin-bottom:20px; display:flex; gap:10px;">
        @foreach ($categories as $cat)
            <a href="{{ route('dashboard', ['category' => $cat->id]) }}"
               style="padding:5px 10px; border:1px solid #ccc; border-radius:4px;
                  background-color: {{ request('category') == $cat->id ? '#ddd' : '#fff' }};">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
    <form action="{{ route('dashboard') }}"
          method="GET"
          class="mb-4">
        <input type="text"
               name="search"
               placeholder="Search services..."
               value="{{ request('search') }}"
               class="border rounded px-3 py-2 w-full max-w-sm">
        <button type="submit"
                class="bg-blue-600 text-white px-3 py-2 rounded ml-2">Search</button>
    </form>

    @auth
        @if (auth()->user()->role === 'admin')
            <div class="mb-4 flex gap-2">
                <a href="{{ route('categories.index') }}"
                   class="text-blue-700 bg-gray-200 p-4">
                    Kelola Kategori
                </a>

                <a href="{{ route('subcategories.index') }}"
                   class="text-blue-700 bg-gray-200 p-4">
                    Kelola Subkategori
                </a>
            </div>
        @endif
    @endauth

    <div class="mb-4"
         style="display:flex; gap:10px; align-items:center;">
        {{-- Tombol lokasi otomatis --}}
        <button type="button"
                id="btn-nearby"
                class="text-blue-700 bg-gray-200 p-4">Layanan Terdekat</button>

        {{-- Form alamat --}}
        <form id="location-form"
              action="{{ route('services.nearby') }}"
              method="get"
              style="display:flex; gap:5px; align-items:center;">
            <div style="position: relative; width: 300px;">
                <input type="text"
                       id="address-input"
                       placeholder="Ketik alamat..."
                       class="form-control"
                       autocomplete="off"
                       required>
                <input type="hidden"
                       name="lat"
                       id="lat">
                <input type="hidden"
                       name="lng"
                       id="lng">
                <ul id="autocomplete-results"
                    style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ccc; max-height: 200px; overflow-y: auto; z-index: 1000; list-style: none; padding: 0; margin: 0; display: none;">
                </ul>
            </div>
            <button type="submit"
                    class="text-blue-700 bg-gray-200 p-4">Cari</button>
        </form>
    </div>

    {{-- Baris Highlight --}}
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
        @php
            $highlightServices = $services->filter(function ($s) {
                return $s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until);
            });
        @endphp

        @forelse($highlightServices as $service)
            @php
                $images = json_decode($service->images, true);
                $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                $profilePhoto =
                    $service->user && $service->user->profile_photo
                        ? asset('storage/' . $service->user->profile_photo)
                        : asset('images/profile-user.png');
            @endphp

            <div
                 style="
                border: 2px gold solid;
                border-radius: 6px;
                width: 200px;
                text-decoration: none;
                color: #000;
                padding: 10px;
                display: flex;
                flex-direction: column;
                background: #fff8dc;
                box-shadow: 0 0 10px rgba(218,165,32,0.5);
                position: relative;
            ">
                @php
                    $userFavorites = auth()->user()->favoriteServices ?? collect();
                    $isFavorited = $userFavorites->contains($service->id);
                @endphp

                <form action="{{ route('services.toggleFavorite', $service->slug) }}"
                      method="POST"
                      style="position: absolute; top: 8px; right: 8px;">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="focus:outline-none">
                        @if ($isFavorited)
                            <span class="text-yellow-500 text-xl">&#9733;</span> {{-- bintang terisi --}}
                        @else
                            <span class="text-gray-400 text-xl">&#9734;</span> {{-- bintang kosong --}}
                        @endif
                    </button>
                </form>
                <div
                     style="
                position: absolute;
                top: 8px;
                left: 8px;
                background: gold;
                color: #000;
                font-weight: bold;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 12px;
            ">
                    HIGHLIGHT</div>

                @if ($mainImage)
                    <img src="{{ $mainImage }}"
                         alt="{{ $service->title }}"
                         style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px; margin-bottom: 8px;" />
                @else
                    <div
                         style="width: 100%; height: 120px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin-bottom: 8px;">
                        No Image
                    </div>
                @endif
                <a href="{{ route('services.show', $service->slug) }}"
                   class="text-gray-600 hover:underline">{{ $service->title }}</a>

                <div style="display: flex; align-items: center; margin-bottom: 6px">
                    <img src="{{ $profilePhoto }}"
                         alt="{{ $service->user->full_name ?? 'N/A' }}"
                         style="width: 24px; height: 24px; border-radius: 50%; margin-right: 6px; object-fit: cover;" />
                    <span style="font-size: 14px">{{ $service->user->full_name ?? 'N/A' }}</span>
                </div>

                <p style="color: green; font-weight: bold; margin-top: auto">
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                </p>

                <div class="flex mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($service->avg_rating))
                            <span class="text-yellow-400">&#9733;</span>
                        @elseif($i - $service->avg_rating < 1)
                            <span class="text-yellow-400">&#9733;</span>
                        @else
                            <span class="text-gray-300">&#9733;</span>
                        @endif
                    @endfor
                </div>
            </div>
        @empty
            {{-- Kosong, tidak ada highlight --}}
        @endforelse
    </div>

    {{-- Baris Normal --}}
    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        @php
            $normalServices = $services->filter(function ($s) {
                return !($s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until));
            });
        @endphp

        @forelse($normalServices as $service)
            @php
                $images = json_decode($service->images, true);
                $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                $profilePhoto =
                    $service->user && $service->user->profile_photo
                        ? asset('storage/' . $service->user->profile_photo)
                        : asset('images/profile-user.png');
            @endphp

            <div
                 style="
                border: 2px #ccc solid;
                border-radius: 6px;
                width: 200px;
                text-decoration: none;
                color: #000;
                padding: 10px;
                display: flex;
                flex-direction: column;
                background: #fff;
                box-shadow: none;
                position: relative;
            ">
                @auth
                    @php
                        $userFavorites = auth()->user()->favoriteServices ?? collect();
                        $isFavorited = $userFavorites->contains($service->id);
                    @endphp

                    <form action="{{ route('services.toggleFavorite', $service->slug) }}"
                          method="POST"
                          style="position: absolute; top: 8px; right: 8px;">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="focus:outline-none">
                            @if ($isFavorited)
                                <span class="text-yellow-500 text-xl">&#9733;</span> {{-- bintang terisi --}}
                            @else
                                <span class="text-gray-400 text-xl">&#9734;</span> {{-- bintang kosong --}}
                            @endif
                        </button>
                    </form>
                @endauth

                @if ($mainImage)
                    <img src="{{ $mainImage }}"
                         alt="{{ $service->title }}"
                         style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px; margin-bottom: 8px;" />
                @else
                    <div
                         style="width: 100%; height: 120px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin-bottom: 8px;">
                        No Image
                    </div>
                @endif

                <a href="{{ route('services.show', $service->slug) }}"
                   class="text-gray-600 hover:underline">{{ $service->title }}</a>

                <div style="display: flex; align-items: center; margin-bottom: 6px">
                    <img src="{{ $profilePhoto }}"
                         alt="{{ $service->user->full_name ?? 'N/A' }}"
                         style="width: 24px; height: 24px; border-radius: 50%; margin-right: 6px; object-fit: cover;" />
                    <span style="font-size: 14px">{{ $service->user->full_name ?? 'N/A' }}</span>
                </div>

                <p style="color: green; font-weight: bold; margin-top: auto">
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                </p>

                <div class="flex mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($service->avg_rating))
                            <span class="text-yellow-400">&#9733;</span>
                        @elseif($i - $service->avg_rating < 1)
                            <span class="text-yellow-400">&#9733;</span>
                        @else
                            <span class="text-gray-300">&#9733;</span>
                        @endif
                    @endfor
                </div>
            </div>
        @empty
            <p>Tidak ada service untuk ditampilkan.</p>
        @endforelse
    </div>


    <script>
        const addressInput = document.getElementById('address-input');
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        const resultsList = document.getElementById('autocomplete-results');
        const form = document.getElementById('location-form');

        let timeout = null;

        addressInput.addEventListener('input', () => {
            clearTimeout(timeout);
            const query = addressInput.value;

            if (query.length < 3) {
                resultsList.style.display = 'none';
                return;
            }

            timeout = setTimeout(() => {
                fetch(
                        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1`
                    )
                    .then(res => res.json())
                    .then(data => {
                        resultsList.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item.display_name;
                                li.style.padding = '5px';
                                li.style.cursor = 'pointer';

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
                    });
            }, 500);
        });

        // hide dropdown kalau klik di luar
        document.addEventListener('click', (e) => {
            if (!addressInput.contains(e.target)) {
                resultsList.style.display = 'none';
            }
        });

        // validasi submit form
        form.addEventListener('submit', (e) => {
            if (!latInput.value || !lngInput.value) {
                e.preventDefault();
                alert('Pilih alamat dari dropdown agar koordinat terisi!');
            }
        });

        // tombol lokasi terdekat
        document.getElementById('btn-nearby').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    latInput.value = position.coords.latitude;
                    lngInput.value = position.coords.longitude;
                    form.submit(); // langsung submit form dengan koordinat
                }, () => {
                    alert('Tidak bisa mengambil lokasi kamu');
                });
            } else {
                alert('Browser tidak mendukung geolocation');
            }
        });
    </script>

</x-app-layout>
