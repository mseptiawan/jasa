<x-app-layout>
    {{-- CSS Kustom & Font Montserrat --}}
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        .text-primary {
            color: #2b3cd7;
        }

        .bg-primary {
            background-color: #2b3cd7;
        }

        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Styling untuk status & loading */
        .status-box {
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
        }

        .status-box.success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-box.loading {
            background-color: #fffbeb;
            color: #b45309;
        }

        .loader {
            border-top-color: #2b3cd7;
            -webkit-animation: spinner 1.2s linear infinite;
            animation: spinner 1.2s linear infinite;
        }

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    {{-- Container Utama --}}
    <div class="container mx-auto py-8 px-4 md:px-8 max-w-2xl mt-16">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-gray-200 shadow-custom">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">Bayar Highlight</h1>
            <p class="text-gray-600 text-center mb-6">Untuk **{{ $service->title }}**</p>

            {{-- Ringkasan Detail Pembayaran --}}
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 font-medium">Durasi Highlight</span>
                    <span class="text-gray-900 font-bold">{{ $highlightDuration }} hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Fee Highlight</span>
                    <span class="text-gray-900 font-bold text-xl">Rp {{ number_format($highlightFee, 0, ',', '.') }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-4 text-center">
                    (Simulasi: Tidak ada pembayaran nyata, highlight langsung aktif setelah form di-submit.)
                </p>
            </div>

            {{-- Form Pembayaran --}}
            <form id="highlightForm" action="{{ route('services.highlight.pay', $service->slug) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="paymentMethod" class="block text-gray-700 font-semibold mb-2">Metode Pembayaran</label>
                    <div class="relative">
                        {{-- Menghapus "appearance-none" agar ikon dropdown bawaan browser muncul --}}
                        <select name="payment_method" id="paymentMethod" class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-primary transition-colors duration-200">
                            <option value="bank_transfer" selected>Bank Transfer</option>
                            <option value="e_wallet">E-Wallet (OVO, GoPay, Dana)</option>
                            <option value="dummy_gateway">Gateway Dummy</option>
                        </select>
                    </div>
                    @error('payment_method')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Info metode pembayaran dinamis (tanpa ikon) --}}
                <div id="paymentInfo" class="mt-2 p-4 bg-gray-100 rounded-lg text-sm text-gray-700">
                    <p>Transfer ke rekening **BCA 123-456-789** a.n. **PT. Contoh.**</p>
                </div>

                <div class="mt-8 text-center">
                    <button type="submit" id="payButton" class="bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition-colors duration-300 text-lg w-full">
                        Bayar & Pesan
                    </button>
                </div>
            </form>

            {{-- Area loading & sukses --}}
            <div id="loadingBox" class="status-box loading hidden mt-6">
                <p class="font-semibold text-orange-700">Memproses pembayaran...</p>
                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-10 w-10 mx-auto my-4"></div>
            </div>

            <div id="successBox" class="status-box success hidden mt-6">
                <p class="font-semibold text-green-700">Pembayaran berhasil! Highlight telah aktif.</p>
                <a href="{{ route('services.highlight') }}" class="text-primary hover:underline block mt-4 font-medium">
                    ← Kembali ke daftar layanan
                </a>
            </div>
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
            let infoText = '';
            if (method === 'bank_transfer') {
                infoText = 'Transfer ke rekening **BCA 123-456-789** a.n. **PT. Contoh.**';
            } else if (method === 'e_wallet') {
                infoText = 'Bayar menggunakan **OVO, GoPay, atau Dana** ke nomor **0812-3456-7890.**';
            } else if (method === 'dummy_gateway') {
                infoText = 'Simulasi gateway online. Klik \'Bayar & Pesan\' untuk checkout dummy.';
            }
            paymentInfo.innerHTML = `<p>${infoText}</p>`;
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
