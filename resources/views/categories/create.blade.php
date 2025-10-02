<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                {{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}
            </h1>
            <p class="text-gray-600 mb-6">Isi detail kategori di bawah ini.</p>

            <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}"
                  method="POST">
                @csrf
                @if (isset($category))
                    @method('PUT')
                @endif

                <!-- Input Nama -->
                <div class="mb-5">
                    <label for="name"
                           class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $category->name ?? '') }}"
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                           placeholder="Masukkan nama kategori">
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Deskripsi -->
                <div class="mb-5">
                    <label for="description"
                           class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                              placeholder="Tulis deskripsi kategori...">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('categories.index') }}"
                       class="px-5 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800">
                        {{ isset($category) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
