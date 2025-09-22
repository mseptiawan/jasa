<x-app-layout>
    <div class="flex flex-col items-center justify-center h-screen text-center px-4">
        <h1 class="text-6xl font-bold mb-4">404</h1>
        <h2 class="text-2xl mb-4">Halaman tidak ditemukan</h2>
        <p class="mb-6">Maaf, halaman yang lo cari tidak ada. Mungkin udah dihapus atau salah ketik URL.</p>
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Kembali ke Dashboard
        </a>
    </div>
</x-app-layout>
