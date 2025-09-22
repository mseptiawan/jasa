<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">{{ isset($category) ? 'Edit' : 'Tambah' }} Kategori</h1>

        <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
            @csrf
            @if(isset($category)) @method('PUT') @endif

            <label class="block mb-2">Nama</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="border p-2 w-full mb-4">
            @error('name')<p class="text-red-500">{{ $message }}</p>@enderror

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description" class="border p-2 w-full mb-4">{{ old('description', $category->description ?? '') }}</textarea>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ isset($category) ? 'Update' : 'Simpan' }}</button>
        </form>
    </div>
</x-app-layout>