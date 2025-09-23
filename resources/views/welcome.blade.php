<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet"
          href="{{ asset('css/app.css') }}">
</head>

<body style="padding:20px; font-family:sans-serif;">

    <header style="margin-bottom:20px;">
        <nav style="display:flex; justify-content:space-between; align-items:center;">
            <h1>{{ config('app.name', 'Laravel') }}</h1>
            <div>
                <a href="{{ route('login') }}"
                   style="margin-right:10px;">Login</a>
                <a href="{{ route('register') }}">Register</a>
            </div>
        </nav>
    </header>
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

    <main>
        {{-- Search form --}}
        <form action="{{ route('home') }}"
              method="GET"
              style="margin-bottom:20px;">
            <input type="text"
                   name="search"
                   placeholder="Cari jasa..."
                   value="{{ request('search') }}"
                   style="padding:6px 10px; border:1px solid #ccc; border-radius:4px;">
            <button type="submit"
                    style="padding:6px 10px; background:#3490dc; color:#fff; border-radius:4px;">Search</button>
        </form>

        {{-- Highlight services --}}
        @php
            $highlightServices = $services->filter(
                fn($s) => $s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until),
            );
        @endphp

        @if ($highlightServices->count() > 0)
            <h2 style="margin-bottom:10px;">Highlight Services</h2>
            <div style="display:flex; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
                @foreach ($highlightServices as $service)
                    @php
                        $images = json_decode($service->images, true);
                        $mainImage = $images[0] ?? null;
                        $profilePhoto = $service->user->profile_photo ?? null;
                    @endphp
                    <div
                         style="border:2px gold solid; border-radius:6px; width:200px; padding:10px; background:#fff8dc; position:relative;">
                        @if ($mainImage)
                            <img src="{{ asset('storage/' . $mainImage) }}"
                                 style="width:100%; height:120px; object-fit:cover; border-radius:4px; margin-bottom:8px;">
                        @else
                            <div
                                 style="width:100%; height:120px; background:#eee; display:flex; align-items:center; justify-content:center; border-radius:4px; margin-bottom:8px;">
                                No Image
                            </div>
                        @endif
                        <span
                              style="position:absolute; top:8px; left:8px; background:gold; padding:2px 6px; font-size:12px; font-weight:bold; border-radius:4px;">HIGHLIGHT</span>

                        <a href="{{ route('services.show', $service->slug) }}"
                           style="display:block; font-weight:bold; margin-bottom:4px; color:#000; text-decoration:none; hover:underline;">
                            {{ $service->title }}
                        </a>

                        <p style="margin:0 0 6px 0; font-size:14px; color:green;">Rp
                            {{ number_format($service->price, 0, ',', '.') }}</p>

                        <div style="display:flex; align-items:center; gap:4px; margin-bottom:4px;">
                            @if ($profilePhoto)
                                <img src="{{ asset('storage/' . $profilePhoto) }}"
                                     style="width:24px; height:24px; border-radius:50%; object-fit:cover;">
                            @else
                                <div style="width:24px; height:24px; border-radius:50%; background:#ccc;"></div>
                            @endif
                            <span style="font-size:14px;">{{ $service->user->full_name ?? 'N/A' }}</span>
                        </div>

                        <div>
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($service->avg_rating))
                                    <span style="color:#facc15;">&#9733;</span>
                                @elseif($i - $service->avg_rating < 1)
                                    <span style="color:#facc15;">&#9733;</span>
                                @else
                                    <span style="color:#ccc;">&#9733;</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Normal services --}}
        @php
            $normalServices = $services->filter(
                fn($s) => !($s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until)),
            );
        @endphp

        <h2 style="margin-bottom:10px;">All Services</h2>
        <div style="display:flex; flex-wrap:wrap; gap:15px;">
            @forelse($normalServices as $service)
                @php
                    $images = json_decode($service->images, true);
                    $mainImage = $images[0] ?? null;
                    $profilePhoto = $service->user->profile_photo ?? null;
                @endphp
                <div
                     style="border:1px solid #ccc; border-radius:6px; width:200px; padding:10px; background:#fff; position:relative;">
                    @if ($mainImage)
                        <img src="{{ asset('storage/' . $mainImage) }}"
                             style="width:100%; height:120px; object-fit:cover; border-radius:4px; margin-bottom:8px;">
                    @else
                        <div
                             style="width:100%; height:120px; background:#eee; display:flex; align-items:center; justify-content:center; border-radius:4px; margin-bottom:8px;">
                            No Image
                        </div>
                    @endif

                    <a href="{{ route('services.show', $service->slug) }}"
                       style="display:block; font-weight:bold; margin-bottom:4px; color:#000; text-decoration:none; hover:underline;">
                        {{ $service->title }}
                    </a>

                    <p style="margin:0 0 6px 0; font-size:14px; color:green;">Rp
                        {{ number_format($service->price, 0, ',', '.') }}</p>

                    <div style="display:flex; align-items:center; gap:4px; margin-bottom:4px;">
                        @if ($profilePhoto)
                            <img src="{{ asset('storage/' . $profilePhoto) }}"
                                 style="width:24px; height:24px; border-radius:50%; object-fit:cover;">
                        @else
                            <div style="width:24px; height:24px; border-radius:50%; background:#ccc;"></div>
                        @endif
                        <span style="font-size:14px;">{{ $service->user->full_name ?? 'N/A' }}</span>
                    </div>

                    <div>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($service->avg_rating))
                                <span style="color:#facc15;">&#9733;</span>
                            @elseif($i - $service->avg_rating < 1)
                                <span style="color:#facc15;">&#9733;</span>
                            @else
                                <span style="color:#ccc;">&#9733;</span>
                            @endif
                        @endfor
                    </div>
                </div>
            @empty
                <p>Tidak ada layanan saat ini.</p>
            @endforelse
        </div>

    </main>

</body>

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

</html>
