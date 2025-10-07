<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Layanan Favorit Anda</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($services as $service)
                @php
                    $images = json_decode($service->images, true);
                    $mainImage = !empty($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                    $profilePhoto = $service->user && $service->user->profile_photo ? asset('storage/' . $service->user->profile_photo) : asset('images/profile-user.png');
                    $isFavorited = auth()->user() && auth()->user()->favoriteServices->contains($service->id);
                @endphp
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 relative">

                    {{-- START: FAVORITE BUTTON --}}
                    <form action="{{ route('services.toggleFavorite', $service->slug) }}" method="POST" class="absolute top-2 right-2 z-10">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="focus:outline-none bg-white p-1 rounded-full shadow-md">
                            @if ($isFavorited)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            @endif
                        </button>
                    </form>
                    {{-- END: FAVORITE BUTTON --}}

                    <a href="{{ route('services.show', $service->slug) }}">
                        @if ($mainImage)
                            <img src="{{ $mainImage }}" alt="{{ $service->title }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">Tidak Ada Gambar</div>
                        @endif
                    </a>

                    <div class="p-4">
                        <a href="{{ route('services.show', $service->slug) }}" class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>

                        <p class="text-lg font-semibold text-green-600 mb-3">Rp {{ number_format($service->price, 0, ',', '.') }}</p>

                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            <img src="{{ $profilePhoto }}" alt="{{ $service->user->full_name ?? 'N/A' }}" class="w-7 h-7 rounded-full object-cover">
                            <span>{{ $service->user->full_name ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
            @empty
                {{-- START: EMPTY STATE DESIGN DENGAN images/listening.png --}}
                <div class="col-span-full flex flex-col items-center justify-center p-12 rounded-xl bg-gray-50 border border-gray-200">
                    <div class="w-32 h-32 mb-4 text-gray-400">
                        {{-- Menggunakan URL asset yang diminta --}}
                        <img src="{{ asset('images/listening.png') }}" alt="Stiker Listening" class="w-full h-full object-contain">
                    </div>

                    <p class="text-xl font-semibold text-gray-700 mt-2 mb-1">
                        Belum Ada Layanan Favorit
                    </p>
                    <p class="text-gray-500 max-w-md text-center">
                        Sepertinya Anda belum menemukan layanan yang cocok untuk dijadikan favorit. Ayo jelajahi dan tekan ikon hati ❤️ pada layanan yang Anda suka!
                    </p>

                    <a href="#" class="mt-5 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150">
                        Jelajahi Layanan
                    </a>
                </div>
                {{-- END: EMPTY STATE DESIGN --}}
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.rating-stars').forEach(container => {
                const orderId = container.id.split('-')[2];
                const hiddenInput = document.getElementById(`rating-input-${orderId}`);
                const stars = container.querySelectorAll('.star-icon');

                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const ratingValue = star.dataset.rating;
                        hiddenInput.value = ratingValue;

                        stars.forEach(s => {
                            if (s.dataset.rating <= ratingValue) {
                                s.classList.add('text-yellow-400');
                                s.classList.remove('text-gray-400');
                            } else {
                                s.classList.add('text-gray-400');
                                s.classList.remove('text-yellow-400');
                            }
                        });
                    });
                });
            });
        });
    </script>
</x-app-layout>
