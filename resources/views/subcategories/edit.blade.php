<x-app-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8 max-w-2xl">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-6">
            {{ isset($subcategory) ? 'Edit Subkategori' : 'Tambah Subkategori' }}
        </h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ isset($subcategory) ? route('subcategories.update', $subcategory->id) : route('subcategories.store') }}"
                  method="POST"
                  class="space-y-6">
                @csrf
                @if (isset($subcategory))
                    @method('PUT')
                @endif

                <!-- Kategori Induk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Induk</label>
                    <select name="category_id"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ old('category_id', $subcategory->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Subkategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Subkategori</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $subcategory->name ?? '') }}"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description"
                              rows="4"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $subcategory->description ?? '') }}</textarea>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('subcategories.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-black rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        {{ isset($subcategory) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
