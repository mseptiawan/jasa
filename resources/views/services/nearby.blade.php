<x-app-layout>
    <style>
        /*
        * Custom Styles from welcome.blade.php
        * This ensures consistency across the site.
        */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc; /* Tailwind gray-50 */
        }

        /* Custom Colors */
        .bg-primary { background-color: #2b3cd7; }
        .text-primary { color: #2b3cd7; }
        .border-primary { border-color: #2b3cd7; }
        .bg-accent { background-color: #ffd231; }
        .text-accent { color: #ffd231; }
        .text-red-500 { color: #ef4444; }

        /* Custom focus ring color */
        .focus\:ring-primary:focus {
            --tw-ring-color: #2b3cd7;
        }

        /* Spinner for loading indicator */
        .loader {
            border: 4px solid #f3f3f3; /* Light grey */
            border-top: 4px solid #2b3cd7; /* Blue */
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        {{-- Tombol Kembali --}}
        <a href="javascript:history.back()" class="text-gray-600 hover:text-primary hover:underline font-semibold flex items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        <h2 class="text-2xl font-bold mb-6 text-gray-900">Layanan Terdekat</h2>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($services as $service)
                @php
                    $images = json_decode($service->images, true);
                    $mainImage = !empty($images) ? asset('storage/' . $images[0]) : null;

                    $profilePhoto =
                        $service->user && $service->user->profile_photo
                            ? asset('storage/' . $service->user->profile_photo)
                            : asset('images/profile-user.png');
                @endphp

                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 relative shadow-md">
                    {{-- Gambar utama --}}
                    @if ($mainImage)
                        <a href="{{ route('services.show', $service->slug) }}">
                            <img src="{{ $mainImage }}"
                                alt="{{ $service->title }}"
                                class="w-full h-40 object-cover">
                        </a>
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                            Tidak Ada Gambar
                        </div>
                    @endif

                    <div class="p-4">
                        {{-- Judul --}}
                        <a href="{{ route('services.show', $service->slug) }}"
                            class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>

                        {{-- Harga --}}
                        <p class="text-lg font-semibold text-green-600 mb-3">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </p>

                        {{-- Profil user --}}
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            <img src="{{ $profilePhoto }}"
                                alt="{{ $service->user->full_name ?? 'N/A' }}"
                                class="w-7 h-7 rounded-full object-cover">
                            <span>{{ $service->user->full_name ?? 'N/A' }}</span>
                        </div>

                        {{-- Rating --}}
                        <div class="flex items-center mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>

                        {{-- Jarak --}}
                        @if (isset($service->distance))
                            <div class="flex items-center text-sm text-gray-500 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ number_format($service->distance, 2) }} km</span>
                            </div>
                        @endif
                    </div>
                </div>

            @empty
                <p class="col-span-full text-center text-gray-500 py-10">Tidak ada layanan terdekat.</p>
            @endforelse
        </div>

        {{-- Tombol refresh lokasi --}}
        <div class="mt-8 text-center">
            <button id="btn-nearby"
                    class="bg-primary text-white font-bold py-3 px-6 rounded-lg shadow-md hover:opacity-90 transition-all">
                Refresh Lokasi
            </button>
        </div>
    </div>

    <script>
        document.getElementById('btn-nearby').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    window.location.href = `/services/nearby?lat=${lat}&lng=${lng}`;
                }, () => {
                    alert('Tidak bisa mengambil lokasi Anda. Silakan periksa izin browser Anda.');
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda.');
            }
        });
    </script>
</x-app-layout>
