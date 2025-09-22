<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Jasa
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- Grid flex --}}
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

                    {{-- Rating --}}
                    <div class="flex mt-1">
                        @for ($i = 1; $i <= 5;
                           $i++)
                           @if($i
                           <=floor($service->avg_rating))
                            <span class="text-yellow-400">&#9733;</span>
                            @elseif($i - $service->avg_rating < 1)
                              <span
                              class="text-yellow-400">&#9733;</span>
                                @else
                                <span class="text-gray-300">&#9733;</span>
                                @endif
                                @endfor
                    </div>

                    <form action="{{ route('admin.services.toggleStatus', $service->slug) }}"
                          method="POST"
                          style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="px-2 py-1 rounded {{ $service->status === 'active' ? 'bg-red-500' : 'bg-green-500' }}">
                            {{ $service->status === 'active' ? 'Suspend' : 'Enable' }}
                        </button>
                    </form>


                </a>
                @empty
                <p>Tidak ada service untuk ditampilkan.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
