<x-app-layout>
    <div class="container mx-auto py-6 max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Kategori</h1>

        <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Kategori</a>

        @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <table class="w-full border">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Nama</th>
                    <th class="p-2 text-left">Slug</th>
                    <th class="p-2 text-left">Deskripsi</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-b">
                    <td class="p-2">{{ $category->name }}</td>
                    <td class="p-2">{{ $category->slug }}</td>
                    <td class="p-2">{{ $category->description ?? '-' }}</td>
                    <td class="p-2">
                        <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>