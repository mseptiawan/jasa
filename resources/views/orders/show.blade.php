<x-app-layout>
    <style>
        /* Define custom colors if not already defined globally */
        .text-primary { color: #2b3cd7; }
        .bg-primary { background-color: #2b3cd7; }
        .text-accent { color: #ffd231; }
        .bg-accent { background-color: #ffd231; }
    </style>

    <div class="container mx-auto py-8 max-w-2xl">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-3">Konfirmasi Pembayaran</h1>

        <div class="bg-white p-6 md:p-8 rounded-xl border border-gray-200 shadow-lg">

            {{-- START: Ringkasan Total --}}
            <div class="bg-primary text-white p-5 rounded-lg mb-6 shadow-md">
                <p class="text-sm font-light mb-1">Total yang Harus Dibayar</p>
                <h2 class="text-4xl font-extrabold tracking-tight">
                    Rp {{ number_format(request('total'), 0, ',', '.') }}
                </h2>
                <div class="mt-3 flex justify-between items-center text-sm font-semibold border-t border-white/30 pt-2">
                    <span>Metode Pembayaran:</span>
                    <span class="bg-white text-primary px-3 py-1 rounded-full text-xs uppercase">
                        {{ strtoupper(str_replace('_', ' ', request('method'))) }}
                    </span>
                </div>
            </div>
            {{-- END: Ringkasan Total --}}


            {{-- START: Detail Transaksi --}}
            <div class="mb-6 space-y-4 text-gray-700">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-900">Invoice:</span>
                    <strong class="text-primary font-extrabold">INV-{{ rand(1000000, 9999999) }}</strong>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-900">Paket Layanan:</span>
                    <span class="text-right">{{ $service->title ?? 'Nama Layanan' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-900">Pelanggan:</span>
                    <span>{{ auth()->user()->name ?? 'Pengguna' }} ({{ auth()->user()->phone ?? 'N/A' }})</span>
                </div>

                <div class="flex justify-between pt-2">
                    <span class="font-semibold text-gray-900">Status Saat Ini:</span>
                    <span class="px-3 py-1 bg-red-100 text-red-700 font-bold rounded-full text-sm">
                        BELUM BAYAR
                    </span>
                </div>
            </div>
            {{-- END: Detail Transaksi --}}


            {{-- START: Instruksi Pembayaran & Batas Waktu --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-3">Langkah Selanjutnya</h3>

                @if (request('method') == 'virtual_account')
                    <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300 mb-4">
                        <p class="text-sm text-gray-600 mb-2">Nomor Rekening Virtual Account Anda:</p>
                        <strong class="text-2xl font-extrabold text-primary block break-all">
                            8808 9206 0119 0836
                        </strong>
                        <p class="text-xs text-gray-500 mt-2">Silakan selesaikan pembayaran ke nomor VA di atas. Layanan akan aktif otomatis setelah konfirmasi pembayaran diterima.</p>
                    </div>
                @elseif(request('method') == 'qris')
                    <p class="text-gray-700 mb-4">
                        Silakan **Scan QRIS** di bawah ini menggunakan aplikasi e-wallet (GoPay, Dana, OVO, LinkAja) atau Mobile Banking Anda.
                    </p>
                    <div class="flex justify-center my-6">
                         {{-- Placeholder QRIS: Ganti dengan logic generate QRIS sesungguhnya --}}
                         <div class="w-40 h-40 bg-gray-200 flex items-center justify-center border border-gray-400 rounded">
                             <span class="text-sm text-gray-500">QRIS CODE</span>
                         </div>
                    </div>
                @elseif(request('method') == 'credit_card')
                    <p class="text-gray-700 mb-4">
                        Anda akan diarahkan ke halaman *gateway* pembayaran yang aman. Masukkan data kartu kredit/debit Anda untuk melanjutkan transaksi.
                    </p>
                    <button class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                        Lanjutkan ke Pembayaran Kartu
                    </button>
                @endif

                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                    <p class="font-bold text-sm mb-1">Batas Waktu Pembayaran:</p>
                    <strong class="text-xl font-extrabold">
                        {{ now()->addDay()->format('d M Y / H:i:s') }}
                    </strong>
                </div>
            </div>
            {{-- END: Instruksi Pembayaran & Batas Waktu --}}

        </div>
    </div>
</x-app-layout>
