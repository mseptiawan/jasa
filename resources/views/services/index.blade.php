<x-app-layout>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="container mx-auto py-6 max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Daftar Layanan Saya</h1>

        <a href="{{ route('services.create') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Layanan</a>

        @if($services->count())
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-2 py-1">Judul</th>
                    <th class="border px-2 py-1">Harga</th>
                    <th class="border px-2 py-1">Kategori</th>
                    <th class="border px-2 py-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td class="border px-2 py-1">{{ $service->title }}</td>
                    <td class="border px-2 py-1">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                    <td class="border px-2 py-1">{{ $service->subcategory->name ?? '-' }}</td>
                    <td class="border px-2 py-1 space-x-2">
                        <a href="{{ route('services.show', $service->slug) }}"
                           class="text-blue-600 underline">Lihat</a>
                        <a href="{{ route('services.edit', $service->slug) }}"
                           class="text-yellow-600 underline">Edit</a>
                        <form action="{{ route('services.destroy', $service->slug) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Hapus layanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Belum ada layanan.</p>
        @endif
    </div>
</x-app-layout>
