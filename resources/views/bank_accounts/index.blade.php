<x-app-layout>
    <div id="main-content" class="py-6 transition-all duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Kondisi jika ada akun --}}
            @if ($accounts->count() > 0)
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Rekening Bank Saya</h1>
                    <a href="{{ route('bank-accounts.create') }}" class="flex items-center gap-2 px-4 py-2 bg-black text-white font-bold rounded-lg hover:bg-gray-800 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                        </svg>
                        Tambah Rekening Bank
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($accounts as $account)
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm transition-transform duration-200 hover:-translate-y-1">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-gray-500">
                                        <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z" />
                                        <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5Zm-18 3.75a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 leading-snug">{{ $account->bank_name }}</h3>
                                    <p class="text-gray-500 text-sm">{{ $account->account_holder }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600 mb-6">
                                <p><strong>Nomor Rekening:</strong> <span class="font-mono">{{ $account->account_number }}</span></p>
                                <p><strong>Pemilik Rekening:</strong> {{ $account->account_holder }}</p>
                            </div>

                            <div class="flex justify-end gap-2 border-t border-gray-200 pt-4">
                                <a href="{{ route('bank-accounts.edit', $account->id) }}" class="flex items-center gap-1 px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                        <path d="m21.732 7.732-3.536-3.536a.75.75 0 0 0-1.06 0l-12.25 12.25a.75.75 0 0 0-.175.253l-2.001 6a.75.75 0 0 0 .972.972l6-2.001a.75.75 0 0 0 .253-.175l12.25-12.25a.75.75 0 0 0 0-1.06Z" />
                                    </svg>
                                    Edit
                                </a>
                                <button
                                    type="button"
                                    class="open-modal-button flex items-center gap-1 px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors"
                                    data-account-id="{{ $account->id }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                        <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                        <path fill-rule="evenodd" d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.133 2.845a.75.75 0 0 1 1.06 0l1.72 1.72 1.72-1.72a.75.75 0 1 1 1.06 1.06l-1.72 1.72 1.72 1.72a.75.75 0 1 1-1.06 1.06L12 15.685l-1.72 1.72a.75.75 0 1 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Kondisi jika tidak ada akun --}}
                <div class="bg-white p-12 rounded-lg text-center border border-gray-200 max-w-lg mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20 text-gray-400 mx-auto mb-4">
                        <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z" />
                        <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5Zm-18 3.75a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada rekening bank</h3>
                    <p class="text-gray-500 mb-6">Silakan tambahkan rekening bank untuk menerima pembayaran.</p>
                    <a href="{{ route('bank-accounts.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white font-bold rounded-lg hover:bg-gray-800 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                        </svg>
                        Tambah Rekening Bank
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Hapus Rekening Bank</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus rekening bank ini? Aksi ini tidak bisa dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors mr-2 close-modal-button">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600 transition-colors">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('delete-modal');
            const mainContent = document.getElementById('main-content');
            const openModalButtons = document.querySelectorAll('.open-modal-button');
            const closeModalButtons = document.querySelectorAll('.close-modal-button');
            const deleteForm = document.getElementById('delete-form');
            const body = document.body;

            function showModal() {
                modal.classList.remove('hidden');
                mainContent.classList.add('filter', 'blur-sm', 'brightness-75'); // Tambah brightness-75 untuk efek gelap
                body.classList.add('overflow-hidden');
            }

            function hideModal() {
                modal.classList.add('hidden');
                mainContent.classList.remove('filter', 'blur-sm', 'brightness-75');
                body.classList.remove('overflow-hidden');
            }

            openModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const accountId = this.getAttribute('data-account-id');
                    const deleteUrl = `{{ url('bank-accounts') }}/${accountId}`;
                    deleteForm.setAttribute('action', deleteUrl);
                    showModal();
                });
            });

            closeModalButtons.forEach(button => {
                button.addEventListener('click', hideModal);
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    hideModal();
                }
            });
        });
    </script>
</x-app-layout>
