<x-app-layout>
    {{-- Custom CSS & Montserrat Font --}}
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        .text-primary {
            color: #2b3cd7;
        }

        .bg-primary {
            background-color: #2b3cd7;
        }

        /* Styling for notification alerts */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-color: #a7f3d0;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
        }

        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Hover effect for card */
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 20px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

    {{-- Main Content Container --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">Daftar Jasa</h1>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($services as $service)
                <div class="bg-white rounded-xl overflow-hidden border border-gray-200 transition-transform duration-200 card-hover">
                    <a href="{{ route('services.show', $service->slug) }}" class="block">
                        @php
                        $images = json_decode($service->images, true);
                        $mainImage = (!empty($images) && count($images) > 0)
                        ? asset('storage/' . $images[0])
                        : null;

                        $profilePhoto = ($service->user && $service->user->profile_photo)
                        ? asset('storage/' . $service->user->profile_photo)
                        : asset('images/profile-user.png');
                        @endphp

                        {{-- Main Image --}}
                        @if($mainImage)
                        <div class="aspect-video w-full overflow-hidden">
                            <img src="{{ $mainImage }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="aspect-video w-full flex items-center justify-center bg-gray-200">
                            <span class="text-gray-500">No Image</span>
                        </div>
                        @endif

                        <div class="p-4">
                            {{-- Title --}}
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $service->title }}</h3>

                            {{-- Creator Info --}}
                            <div class="flex items-center mb-2">
                                <img src="{{ $profilePhoto }}" alt="{{ $service->user->full_name ?? 'N/A' }}" class="w-6 h-6 rounded-full object-cover mr-2" />
                                <span class="text-sm text-gray-600">{{ $service->user->full_name ?? 'N/A' }}</span>
                            </div>

                            {{-- Rating --}}
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($service->avg_rating))
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.27l-6.18 3.24L7 14.14l-5-4.87 6.91-1.01L12 2z" />
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.27l-6.18 3.24L7 14.14l-5-4.87 6.91-1.01L12 2z" />
                                    </svg>
                                    @endif
                                @endfor
                            </div>

                            {{-- Price --}}
                            <p class="text-lg font-bold text-primary mt-auto">
                                Rp {{ number_format($service->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>

                    {{-- Admin Action Button --}}
                    <div class="border-t border-gray-200 p-4">
                        <form action="{{ route('admin.services.toggleStatus', $service->slug) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-center py-2 rounded-lg font-semibold transition-colors duration-200
                                {{ $service->status === 'active' ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-green-500 text-white hover:bg-green-600' }}">
                                {{ $service->status === 'active' ? 'Suspend' : 'Enable' }}
                            </button>
                        </form>
                    </div>
                </div>

                @empty
                <div class="col-span-full text-center py-10 text-gray-500 bg-white p-8 rounded-lg border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-xl font-semibold">Tidak ada jasa untuk ditampilkan.</p>
                    <p class="mt-2 text-gray-500">
                        Anda bisa menambahkan jasa baru untuk memulai.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
