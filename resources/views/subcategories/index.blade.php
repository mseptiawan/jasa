<x-app-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-6">
            Manajemen Subkategori
        </h1>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Daftar Subkategori</h2>
                <a href="{{ route('subcategories.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    + Tambah Subkategori
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 px-4 py-3 rounded-lg bg-green-100 text-green-800 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="divide-y divide-gray-200">
                @forelse($subcategories as $subcategory)
                    <div
                         class="bg-gray-50 rounded-lg p-5 flex flex-col md:flex-row md:items-center md:justify-between transition-colors duration-200 hover:bg-gray-100">

                        <div class="flex-1 mb-4 md:mb-0">
                            <p class="text-sm text-gray-500 font-medium">Nama</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $subcategory->name }}</p>
                        </div>

                        <div class="flex-1 mb-4 md:mb-0">
                            <p class="text-sm text-gray-500 font-medium">Kategori Induk</p>
                            <p class="text-base text-gray-800">{{ $subcategory->category->name }}</p>
                        </div>

                        <div class="flex-1 mb-4 md:mb-0">
                            <p class="text-sm text-gray-500 font-medium">Slug</p>
                            <p class="text-base text-gray-800">{{ $subcategory->slug }}</p>
                        </div>

                        <div class="flex space-x-3 md:justify-end">
                            <a href="{{ route('subcategories.edit', $subcategory->id) }}"
                               class="px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                Edit
                            </a>
                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus subkategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500 font-medium">
                        Belum ada subkategori yang tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
