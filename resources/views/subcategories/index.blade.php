<x-app-layout>
    <div class="container mx-auto py-6 max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Subkategori</h1>

        <a href="{{ route('subcategories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Subkategori</a>

        @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <table class="w-full border">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Nama</th>
                    <th class="p-2 text-left">Kategori Induk</th>
                    <th class="p-2 text-left">Slug</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subcategories as $subcategory)
                <tr class="border-b">
                    <td class="p-2">{{ $subcategory->name }}</td>
                    <td class="p-2">{{ $subcategory->category->name }}</td>
                    <td class="p-2">{{ $subcategory->slug }}</td>
                    <td class="p-2">
                        <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus?')">
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