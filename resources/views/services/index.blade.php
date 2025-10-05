<x-app-layout>
    {{-- CSS Kustom & Font Montserrat --}}
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        /* Kelas untuk efek blur pada konten utama */
        .blur-background {
            filter: blur(5px);
            transition: filter 0.3s ease-in-out;
            pointer-events: none;
            /* Mencegah interaksi dengan konten di belakang modal */
        }

        .text-primary {
            color: #2b3cd7;
        }

        .bg-primary {
            background-color: #2b3cd7;
        }

        /* Styling untuk pesan notifikasi */
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

        /* Pastikan modal berada di atas konten yang di-blur */
        #deleteModal {
            z-index: 1000;
        }
    </style>

    {{-- Konten utama dibungkus dalam div baru --}}
    <div id="content-wrapper">
        <div class="container mx-auto py-8 px-4 md:px-8 max-w-4xl mt-16">

            {{-- Pesan Notifikasi --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white p-6 md:p-8 rounded-2xl border border-gray-200">

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4 md:mb-0">Daftar Layanan Saya</h1>
                    <a href="{{ route('services.create') }}"
                       class="text-primary hover:underline transition-colors duration-300 text-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                  clip-rule="evenodd" />
                        </svg>
                        Tambah Layanan
                    </a>
                </div>

                @if ($services->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                        Judul</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                        Harga</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                        Kategori</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($services as $service)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td>
                                            <a href="{{ route('services.show', $service->slug) }}"
                                               class="text-blue-600 hover:underline">
                                                {{ $service->title }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                            {{ number_format($service->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $service->subcategory->name ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                                            {{-- Ikon Lihat --}}
                                            <a href="{{ route('services.show', $service->slug) }}"
                                               class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                               title="Lihat">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-5 w-5"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Ikon Edit --}}
                                            <a href="{{ route('services.edit', $service->slug) }}"
                                               class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                               title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-5 w-5"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Ikon Hapus yang memicu modal --}}
                                            <form action="{{ route('services.destroy', $service->slug) }}"
                                                  method="POST"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="text-gray-500 hover:text-red-600 transition-colors duration-200 delete-button"
                                                        title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-5 w-5"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="2">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.013 21H7.987a2 2 0 01-1.92-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-10 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="mx-auto h-16 w-16 text-gray-400"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-xl font-semibold">Belum ada layanan yang dibuat.</p>
                        <p class="mt-2">Mulai buat layanan pertama Anda untuk menampilkannya di sini.</p>
                        <a href="{{ route('services.create') }}"
                           class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Buat Layanan Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div> {{-- Penutup div #content-wrapper --}}

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center">
        <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.013 21H7.987a2 2 0 01-1.92-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Hapus Layanan</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus layanan ini? Tindakan ini tidak bisa dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3 mt-4">
                    <button id="closeModalButton"
                            class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md hover:bg-gray-300 focus:outline-none transition duration-150 ease-in-out">
                        Batal
                    </button>
                    <button id="confirmDeleteButton"
                            class="px-4 py-2 ml-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none transition duration-150 ease-in-out">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contentWrapper = document.getElementById('content-wrapper');
            const deleteModal = document.getElementById('deleteModal');
            const closeModalButton = document.getElementById('closeModalButton');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            let formToDelete = null;

            // Fungsi untuk menampilkan modal
            function openModal() {
                deleteModal.classList.remove('hidden');
                contentWrapper.classList.add('blur-background');
            }

            // Fungsi untuk menyembunyikan modal
            function closeModal() {
                deleteModal.classList.add('hidden');
                contentWrapper.classList.remove('blur-background');
            }

            // Loop untuk semua tombol hapus
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    formToDelete = this.closest('form');
                    openModal();
                });
            });

            // Tombol 'Batal' di dalam modal
            closeModalButton.addEventListener('click', function() {
                closeModal();
            });

            // Tombol 'Hapus' di dalam modal
            confirmDeleteButton.addEventListener('click', function() {
                if (formToDelete) {
                    formToDelete.submit();
                    closeModal(); // Pastikan blur hilang setelah submit (meskipun halaman akan refresh)
                }
            });

            // Menutup modal jika klik di luar area modal
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    closeModal();
                }
            });

            // Menutup modal dengan tombol Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
</x-app-layout>
