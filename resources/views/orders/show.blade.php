<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Informasi Pembayaran</h1>

        <p>Hai {{ auth()->user()->name }} ({{ auth()->user()->phone }})</p>
        <p>Total Bayar: <strong>Rp {{ request('total') }}</strong></p>
        <p>Metode: <strong>{{ strtoupper(str_replace('_', ' ', request('method'))) }}</strong></p>

        <p>TRX Invoice: <strong>INV-{{ rand(1000000, 9999999) }}</strong></p>
        <p>Paket Layanan: <strong>{{ $service->title }}</strong></p>
        <p>Status Pembayaran: <strong>BELUM BAYAR</strong></p>

        @if (request('method') == 'virtual_account')
            <p>No Rekening (VA): <strong>8808 9206 0119 0836</strong></p>
            <p>Silahkan selesaikan pembayaran ke nomor rekening di atas. Internet akan aktif otomatis setelah
                konfirmasi.</p>
        @elseif(request('method') == 'qris')
            <p>Scan QRIS menggunakan aplikasi e-wallet Anda.</p>
        @elseif(request('method') == 'credit_card')
            <p>Masukkan data kartu kredit di halaman selanjutnya untuk melanjutkan pembayaran.</p>
        @endif

        <p class="mt-4">Pastikan anda membayar sebelum masa berlaku habis:
            <strong>{{ now()->addDay()->format('M d Y / H:i:s') }}</strong>
        </p>
    </div>
</x-app-layout>
