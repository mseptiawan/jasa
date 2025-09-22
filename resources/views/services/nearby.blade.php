<x-app-layout>
    <h2>Layanan Terdekat</h2>

    @if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
    @endif

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
            $images = json_decode($service->images, true);
            $mainImage = (!empty($images) && count($images) > 0)
            ? asset('storage/' . $images[0])
            : null;

            $profilePhoto = ($service->user && $service->user->profile_photo)
            ? asset('storage/' . $service->user->profile_photo)
            : asset('images/profile-user.png');
            @endphp

            @if($mainImage)
            <img src="{{ $mainImage }}"
                 alt="{{ $service->title }}"
                 style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px; margin-bottom: 8px;" />
            @else
            <div
                 style="width: 100%; height: 120px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin-bottom: 8px;">
                No Image
            </div>
            @endif

            <h3 style="margin: 0 0 8px 0; font-weight: bold">
                {{ $service->title }}
            </h3>

            <div style="display: flex; align-items: center; margin-bottom: 6px">
                <img src="{{ $profilePhoto }}"
                     alt="{{ $service->user->full_name ?? 'N/A' }}"
                     style="width: 24px; height: 24px; border-radius: 50%; margin-right: 6px; object-fit: cover;" />
                <span style="font-size: 14px">{{ $service->user->full_name ?? 'N/A' }}</span>
            </div>

            <p style="color: green; font-weight: bold; margin: 2px 0">
                ${{ number_format($service->price, 2) }}
            </p>

            @if(isset($service->distance))
            <p style="font-size: 13px; color: #555">
                <i class="fa fa-map-marker"
                   style="color:red"></i>
                {{ number_format($service->distance, 2) }} km
            </p>
            @endif
        </a>
        @empty
        <p>Tidak ada layanan terdekat.</p>
        @endforelse
    </div>

    <div class="mb-4">
        <button id="btn-nearby"
                class="btn btn-info">Refresh Lokasi</button>
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
