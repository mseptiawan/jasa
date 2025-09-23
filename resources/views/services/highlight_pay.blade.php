<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Bayar Highlight: {{ $service->title }}</h1>

        <div class="mb-4 p-4 border rounded bg-gray-100">
            <p>Durasi Highlight: {{ $highlightDuration }} hari</p>
            <p>Fee Highlight: Rp {{ number_format($highlightFee, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-600">
                (Simulasi: Tidak ada pembayaran nyata, highlight langsung aktif.)
            </p>
        </div>

        {{-- Form --}}
        <form id="highlightForm"
              action="{{ route('services.highlight.pay', $service->slug) }}"
              method="POST"
              class="p-4 border rounded space-y-4">
            @csrf

            <label class="block mt-2">
                Metode Pembayaran:
                <select name="payment_method"
                        id="paymentMethod"
                        class="border rounded px-2 py-1 w-full">
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="e_wallet">E-Wallet (OVO, GoPay, Dana)</option>
                    <option value="dummy_gateway">Gateway Dummy</option>
                </select>
                @error('payment_method')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </label>

            {{-- Info metode pembayaran --}}
            <div id="paymentInfo"
                 class="mt-2 text-sm text-gray-700">
                Transfer ke rekening BCA 123-456-789 a.n. PT. Contoh.
            </div>

            <button type="submit"
                    id="payButton"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Bayar & Pesan
            </button>
        </form>

        {{-- Area loading & sukses --}}
        <div id="loadingBox"
             class="hidden mt-4 p-4 border rounded text-center bg-yellow-100">
            <p class="font-semibold">Memproses pembayaran...</p>
            <div class="animate-spin mt-2 border-4 border-gray-300 border-t-green-500 rounded-full w-8 h-8 mx-auto">
            </div>
        </div>

        <div id="successBox"
             class="hidden mt-4 p-4 border rounded text-center bg-green-100">
            <p class="font-semibold text-green-700">Pembayaran berhasil! Highlight telah aktif.</p>
            <a href="{{ route('services.highlight') }}"
               class="text-blue-500 hover:underline block mt-2">
                ← Kembali ke daftar layanan
            </a>
        </div>
    </div>

    <script>
        const paymentMethod = document.getElementById('paymentMethod');
        const paymentInfo = document.getElementById('paymentInfo');
        const form = document.getElementById('highlightForm');
        const payButton = document.getElementById('payButton');
        const loadingBox = document.getElementById('loadingBox');
        const successBox = document.getElementById('successBox');

        // Ganti info pembayaran sesuai metode
        paymentMethod.addEventListener('change', () => {
            const method = paymentMethod.value;
            if (method === 'bank_transfer') {
                paymentInfo.textContent =
                    "Transfer ke rekening BCA 123-456-789 a.n. PT. Contoh.";
            } else if (method === 'e_wallet') {
                paymentInfo.textContent =
                    "Bayar menggunakan OVO, GoPay, atau Dana ke nomor 0812-3456-7890.";
            } else if (method === 'dummy_gateway') {
                paymentInfo.textContent =
                    "Simulasi gateway online. Klik 'Bayar & Pesan' untuk checkout dummy.";
            }
        });

        // Simulasi proses pembayaran
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // cegah submit langsung

            payButton.disabled = true;
            loadingBox.classList.remove('hidden');

            // Delay 3 detik seolah proses
            setTimeout(() => {
                loadingBox.classList.add('hidden');
                successBox.classList.remove('hidden');

                // otomatis submit ke server setelah sukses (opsional)
                // form.submit(); // kalau mau tetep update DB
            }, 3000);
        });
    </script>
</x-app-layout>
