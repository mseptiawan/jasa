<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">{{ isset($subcategory) ? 'Edit' : 'Tambah' }} Subkategori</h1>

        <form action="{{ isset($subcategory) ? route('subcategories.update', $subcategory->id) : route('subcategories.store') }}" method="POST">
            @csrf
            @if(isset($subcategory)) @method('PUT') @endif

            <label class="block mb-2">Kategori Induk</label>
            <select name="category_id" class="border p-2 w-full mb-4">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (old('category_id', $subcategory->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>

            @error('category_id')<p class="text-red-500">{{ $message }}</p>@enderror

            <label class="block mb-2">Nama Subkategori</label>
            <input type="text" name="name" value="{{ old('name', $subcategory->name ?? '') }}" class="border p-2 w-full mb-4">
            @error('name')<p class="text-red-500">{{ $message }}</p>@enderror

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description" class="border p-2 w-full mb-4">{{ old('description', $subcategory->description ?? '') }}</textarea>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ isset($subcategory) ? 'Update' : 'Simpan' }}</button>
        </form>
    </div>
</x-app-layout>