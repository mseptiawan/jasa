<x-app-layout>
    <h2 style="margin-bottom: 20px; font-size: 24px; font-weight: bold;">Layanan Terdekat</h2>

    @if (session('error'))
        <p style="color: red; margin-bottom: 20px;">{{ session('error') }}</p>
    @endif

    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        @forelse($services as $service)
            @php
                $images = json_decode($service->images, true);
                $mainImage = !empty($images) ? asset('storage/' . $images[0]) : null;

                $profilePhoto =
                    $service->user && $service->user->profile_photo
                        ? asset('storage/' . $service->user->profile_photo)
                        : asset('images/profile-user.png');
            @endphp

            <div style="
                border: 1px solid #ccc;
                border-radius: 8px;
                width: 200px;
                padding: 10px;
                display: flex;
                flex-direction: column;
                background: #fff;
                transition: transform 0.2s;
            "
                 onmouseover="this.style.transform='scale(1.03)'"
                 onmouseout="this.style.transform='scale(1)'">

                {{-- Gambar utama --}}
                @if ($mainImage)
                    <img src="{{ $mainImage }}"
                         alt="{{ $service->title }}"
                         style="
                        width: 100%;
                        height: 120px;
                        object-fit: cover;
                        border-radius: 6px;
                        margin-bottom: 8px;
                    ">
                @else
                    <div
                         style="
                        width: 100%;
                        height: 120px;
                        background: #eee;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 6px;
                        margin-bottom: 8px;
                        font-size: 14px;
                        color: #888;
                    ">
                        No Image</div>
                @endif

                {{-- Judul --}}
                <a href="{{ route('services.show', $service->slug) }}"
                   style="
                    font-weight: bold;
                    color: #000;
                    margin-bottom: 6px;
                    text-decoration: none;
                "
                   class="hover:underline">{{ $service->title }}</a>

                {{-- Profil user --}}
                <div style="display: flex; align-items: center; margin-bottom: 6px; gap: 6px;">
                    <img src="{{ $profilePhoto }}"
                         alt="{{ $service->user->full_name ?? 'N/A' }}"
                         style="
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        object-fit: cover;
                    ">
                    <span style="font-size: 14px;">{{ $service->user->full_name ?? 'N/A' }}</span>
                </div>

                {{-- Harga --}}
                <p style="color: green; font-weight: bold; margin-bottom: 6px;">
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                </p>

                {{-- Rating --}}
                <div style="display: flex; gap: 2px; margin-bottom: 6px;">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($service->avg_rating))
                            <span style="color: #facc15;">&#9733;</span>
                        @elseif($i - $service->avg_rating < 1)
                            <span style="color: #facc15;">&#9733;</span>
                        @else
                            <span style="color: #ccc;">&#9733;</span>
                        @endif
                    @endfor
                </div>

                {{-- Jarak --}}
                @if (isset($service->distance))
                    <p style="font-size: 13px; color: #555; margin-top: auto;">
                        <i class="fa fa-map-marker"
                           style="color:red;"></i>
                        {{ number_format($service->distance, 2) }} km
                    </p>
                @endif
            </div>

        @empty
            <p>Tidak ada layanan terdekat.</p>
        @endforelse
    </div>

    {{-- Tombol refresh lokasi --}}
    <div style="margin-top: 20px;">
        <button id="btn-nearby"
                style="
            background-color: #3490dc;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        ">Refresh
            Lokasi</button>
    </div>

    <script>
        document.getElementById('btn-nearby').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    window.location.href = `/services/nearby?lat=${lat}&lng=${lng}`;
                }, () => {
                    alert('Tidak bisa mengambil lokasi kamu');
                });
            } else {
                alert('Browser tidak mendukung geolocation');
            }
        });
    </script>
</x-app-layout>
