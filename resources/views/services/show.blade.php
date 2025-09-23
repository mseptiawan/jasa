<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-2">{{ $service->title }}</h1>

        {{-- Kategori --}}
        <p class="text-sm text-gray-600 mb-2">
            Kategori: {{ $service->subcategory->category->name ?? '-' }}
            / Subkategori: {{ $service->subcategory->name ?? '-' }}
        </p>

        <p><strong>Deskripsi:</strong> {{ $service->description }}</p>
        <p><strong>Harga:</strong> Rp {{ number_format($service->price, 0, ',', '.') }}</p>
        <p><strong>Jenis Pekerjaan:</strong> {{ $service->job_type ?? '-' }}</p>
        <p><strong>Pengalaman:</strong> {{ $service->experience ?? '-' }}</p>
        <p><strong>Industri:</strong> {{ $service->industry ?? '-' }}</p>
        <p><strong>Kontak:</strong> {{ $service->contact ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $service->address ?? '-' }}</p>

        @if($service->latitude && $service->longitude)
        <p><strong>Koordinat:</strong> {{ $service->latitude }}, {{ $service->longitude }}</p>
        @endif

        {{-- Gambar --}}
        @if($service->images)
        <h2 class="text-xl mt-4">Gambar</h2>
        <div class="flex space-x-2 mt-2">
            @foreach(json_decode($service->images) as $img)
            <img src="{{ asset('storage/'.$img) }}"
                 class="w-32 h-32 object-cover border" />
            @endforeach
        </div>
        @endif

        {{-- Chat --}}
        <a href="{{ route('conversations.start') }}"
           onclick="event.preventDefault(); document.getElementById('start-chat-{{ $service->id }}').submit();"
           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-4 inline-block">
            Chat
        </a>
        <form id="start-chat-{{ $service->id }}"
              action="{{ route('conversations.start') }}"
              method="POST"
              class="hidden">
            @csrf
            <input type="hidden"
                   name="seller_id"
                   value="{{ $service->user->id }}" />
            <input type="hidden"
                   name="product_id"
                   value="{{ $service->id }}" />
        </form>

        {{-- Button Pesan --}}
        <div class="mt-4">
            <a href="{{ route('orders.create', ['service' => $service->slug]) }}"
               class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Pesan & Bayar
            </a>
        </div>

        <div class="mt-4">
            <a href="{{ route('services.index') }}"
               class="text-blue-500 hover:underline">← Kembali ke daftar</a>
        </div>
    </div>
    {{-- Identitas Penyedia Jasa --}}
    <h2 class="text-xl mt-6 mb-2">Penyedia Jasa</h2>
    <div class="border p-3 rounded mb-4 flex items-center space-x-4">
        @if($service->user->profile_photo)
        <img src="{{ asset('storage/'.$service->user->profile_photo) }}"
             class="w-16 h-16 object-cover rounded-full" />
        @endif
        <div>
            <p><strong>Nama:</strong> {{ $service->user->full_name }}</p>
            <p><strong>Email:</strong> {{ $service->user->email }}</p>
            <p><strong>Bio:</strong> {{ $service->user->bio ?? '-' }}</p>
            @if($service->user->website)
            <p><strong>Website:</strong> <a href="{{ $service->user->website }}"
                   target="_blank"
                   class="text-blue-500 underline">{{ $service->user->website }}</a></p>
            @endif
            @if($service->user->linkedin)
            <p><strong>LinkedIn:</strong> <a href="{{ $service->user->linkedin }}"
                   target="_blank"
                   class="text-blue-500 underline">{{ $service->user->linkedin }}</a></p>
            @endif
            @if($service->user->instagram)
            <p><strong>Instagram:</strong> <a href="{{ $service->user->instagram }}"
                   target="_blank"
                   class="text-blue-500 underline">{{ $service->user->instagram }}</a></p>
            @endif
        </div>
    </div>
    <h2 class="text-xl mt-6 mb-2">Review</h2>
    @if($service->reviews && $service->reviews->count() > 0)
    @foreach($service->reviews as $review)
    <div class="border p-3 rounded mb-2">
        <p><strong>Rating:</strong> {{ $review->rating }}/5</p>
        <p><strong>Komentar:</strong> {{ $review->comment ?? '-' }}</p>
        <p class="text-sm text-gray-500">Dibuat: {{ $review->created_at->format('d M Y H:i') }}</p>
    </div>
    @endforeach
    @else
    <p>Belum ada review untuk produk ini.</p>
    @endif

    <h2 class="text-xl mt-8 mb-4">Jasa Lainnya</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 20px">
        @forelse($services as $otherService)
        @if($otherService->slug !== $service->slug)
        <a href="{{ route('services.show', $otherService->slug) }}"
           style="border:1px solid #ccc;border-radius:6px;width:200px;text-decoration:none;color:#000;padding:10px;display:flex;flex-direction:column;">
            @php
            $images = json_decode($otherService->images, true);
            $mainImage = (!empty($images) && count($images) > 0) ? asset('storage/' . $images[0]) : null;
            $profilePhoto = ($otherService->user && $otherService->user->profile_photo)
            ? asset('storage/' . $otherService->user->profile_photo)
            : asset('images/profile-user.png');
            @endphp

            @if($mainImage)
            <img src="{{ $mainImage }}"
                 alt="{{ $otherService->title }}"
                 style="width:100%;height:120px;object-fit:cover;border-radius:4px;margin-bottom:8px;" />
            @else
            <div
                 style="width:100%;height:120px;background:#eee;display:flex;align-items:center;justify-content:center;border-radius:4px;margin-bottom:8px;">
                No Image
            </div>
            @endif

            <h3 style="margin:0 0 8px 0;font-weight:bold">{{ $otherService->title }}</h3>

            <div style="display:flex;align-items:center;margin-bottom:6px;">
                <img src="{{ $profilePhoto }}"
                     alt="{{ $otherService->user->full_name ?? 'N/A' }}"
                     style="width:24px;height:24px;border-radius:50%;margin-right:6px;object-fit:cover;" />
                <span style="font-size:14px">{{ $otherService->user->full_name ?? 'N/A' }}</span>
            </div>

            <p style="color:green;font-weight:bold;margin-top:auto">Rp {{ number_format($otherService->price,0,',','.')
                }}</p>
            <div class="flex mt-1">
                @for ($i = 1; $i <= 5;
                   $i++)
                   @if($i
                   <=floor($otherService->avg_rating))
                    <span class="text-yellow-400">&#9733;</span> {{-- bintang penuh --}}
                    @elseif($i - $otherService->avg_rating < 1)
                      <span
                      class="text-yellow-400">&#9733;</span> {{-- bintang setengah bisa custom --}}
                        @else
                        <span class="text-gray-300">&#9733;</span> {{-- bintang kosong --}}
                        @endif
                        @endfor
            </div>
        </a>
        @endif
        @empty
        <p>Tidak ada jasa lain untuk ditampilkan.</p>
        @endforelse
    </div>
</x-app-layout>
