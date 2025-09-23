<x-app-layout>
    <div style="display: flex; flex-wrap: wrap; gap: 20px">
        @forelse($services as $service)
            <div
                 style="border:1px solid #ccc; border-radius:6px; width:200px; text-decoration:none; color:#000; padding:10px; display:flex; flex-direction:column;">

                @php
                    $images = json_decode($service->images, true);
                    $mainImage = !empty($images) ? asset('storage/' . $images[0]) : null;
                    $profilePhoto =
                        $service->user && $service->user->profile_photo
                            ? asset('storage/' . $service->user->profile_photo)
                            : asset('images/profile-user.png');
                @endphp

                @if ($mainImage)
                    <img src="{{ $mainImage }}"
                         alt="{{ $service->title }}"
                         style="width:100%; height:120px; object-fit:cover; border-radius:4px; margin-bottom:8px;">
                @else
                    <div
                         style="width:100%; height:120px; background:#eee; display:flex; align-items:center; justify-content:center; border-radius:4px; margin-bottom:8px;">
                        No Image</div>
                @endif
                <a href="{{ route('services.show', $service->slug) }}"
                   class="text-gray-600 hover:underline">{{ $service->title }}</a>

                <div style="display:flex; align-items:center; margin-bottom:6px">
                    <img src="{{ $profilePhoto }}"
                         alt="{{ $service->user->full_name ?? 'N/A' }}"
                         style="width:24px; height:24px; border-radius:50%; margin-right:6px; object-fit:cover;">
                    <span style="font-size:14px">{{ $service->user->full_name ?? 'N/A' }}</span>
                </div>

                <p style="color:green; font-weight:bold; margin-top:auto">Rp
                    {{ number_format($service->price, 0, ',', '.') }}
                </p>

                <div class="flex mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($service->avg_rating))
                            <span class="text-yellow-400">&#9733;</span>
                        @else
                            <span class="text-gray-300">&#9733;</span>
                        @endif
                    @endfor
                </div>

                <form action="{{ route('services.toggleFavorite', $service->slug) }}"
                      method="POST"
                      class="mt-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="px-2 py-1 rounded {{ auth()->user()->favoriteServices->contains($service->id) ? 'bg-red-500 text-white' : 'bg-gray-300' }}">
                        {{ auth()->user()->favoriteServices->contains($service->id) ? 'Unfavorite' : 'Favorite' }}
                    </button>
                </form>

            </div>
        @empty
            <p>Tidak ada favorit.</p>
        @endforelse
    </div>
</x-app-layout>
