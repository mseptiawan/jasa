<x-app-layout>
    <div class="container mx-auto py-6 max-w-xl">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center">
            <div class="mb-6">
                {{-- ICON LOADING YANG SUDAH DIPERBAIKI --}}
                <svg class="animate-spin h-20 w-20 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <h1 class="text-3xl font-bold text-gray-900 mt-4">Memproses Pembayaran</h1>
                <p class="text-gray-500 mt-2">Mohon tunggu sebentar, pembayaran Anda sedang dikonfirmasi.</p>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-6 space-y-4 text-left">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500">Pelanggan</p>
                    <p class="text-sm font-bold text-gray-800">{{ auth()->user()->full_name }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500">Nomor HP</p>
                    <p class="text-sm font-bold text-gray-800">{{ $order->customer_phone }}</p>
                </div>
                <div class="flex justify-between items-center border-t border-gray-200 pt-4 mt-4">
                    <p class="text-lg font-semibold text-gray-800">Total Bayar</p>
                    <p class="text-xl font-bold text-black">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500">Metode Pembayaran</p>
                    <p class="text-sm font-bold text-gray-800">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500">Paket Layanan</p>
                    <p class="text-sm font-bold text-gray-800">{{ $service->title }}</p>
                </div>
            </div>

            <div class="bg-gray-100 rounded-lg p-4 mt-6 text-sm text-gray-700">
                <p>Simulasi pembayaran online sedang diproses. Halaman ini akan otomatis dialihkan setelah pembayaran dikonfirmasi.</p>
            </div>

            {{-- Elemen baru untuk menampilkan timer --}}
            <p class="mt-4 text-center text-gray-500 text-sm">
                Harap tunggu, Anda akan dialihkan dalam <span id="countdown" class="font-bold text-blue-600">15</span> detik.
            </p>
        </div>
    </div>

    <script>
        @if ($order->payment_method == 'dummy_gateway')
            const countdownElement = document.getElementById('countdown');
            let timeLeft = 15;

            function updateCountdown() {
                countdownElement.textContent = timeLeft;
                if (timeLeft > 0) {
                    timeLeft--;
                    setTimeout(updateCountdown, 1000);
                }
            }

            setTimeout(() => {
                window.location.href = "{{ route('orders.store', $order->id) }}";
            }, 15000);

            updateCountdown();
        @endif
    </script>
</x-app-layout>
