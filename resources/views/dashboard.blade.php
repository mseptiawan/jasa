<x-app-layout>
    <!-- <h2>Dashboard</h2> -->
    @auth
    @if(auth()->user()->role === 'admin')
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

    <div style="display: flex; flex-wrap: wrap; gap: 20px">
        @forelse($services as $service)
        <a href="{{ route('services.show', $service->slug) }}"
           style="
                border: 1px solid #ccc;
                border-radius: 6px;
                width: 200px;
                text-decoration: none;
                color: #000;
                padding: 10px;
                display: flex;
                flex-direction: column;
            ">
            @php
            // Ambil gambar utama service
            $images = json_decode($service->images, true);
            $mainImage = (!empty($images) && count($images) > 0)
            ? asset('storage/' . $images[0])
            : null;

            // Ambil foto profil user dengan fallback
            $profilePhoto = ($service->user && $service->user->profile_photo)
            ? asset('storage/' . $service->user->profile_photo)
            : asset('images/profile-user.png');
            @endphp


            {{-- Gambar utama --}}
            @if($mainImage)
            <img src="{{ $mainImage }}"
                 alt="{{ $service->title }}"
                 style="
                    width: 100%;
                    height: 120px;
                    object-fit: cover;
                    border-radius: 4px;
                    margin-bottom: 8px;
                " />
            @else
            <div style="
                    width: 100%;
                    height: 120px;
                    background: #eee;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 4px;
                    margin-bottom: 8px;
                ">
                No Image
            </div>
            @endif

            {{-- Judul --}}
            <h3 style="margin: 0 0 8px 0; font-weight: bold">
                {{ $service->title }}
            </h3>

            {{-- Pembuat --}}
            <div style="display: flex; align-items: center; margin-bottom: 6px">
                <img src="{{ $profilePhoto }}"
                     alt="{{ $service->user->full_name ?? 'N/A' }}"
                     style="
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        margin-right: 6px;
                        object-fit: cover;
                    " />
                <span style="font-size: 14px">{{ $service->user->full_name ?? 'N/A' }}</span>
            </div>

            {{-- Harga --}}
            <p style="color: green; font-weight: bold; margin-top: auto">
                Rp {{ number_format($service->price, 0, ',', '.') }}
            </p>

        </a>
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
                fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1`)
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
