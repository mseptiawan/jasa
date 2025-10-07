<x-app-layout>
    {{-- CSS Kustom & Font Montserrat --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap');

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
                    <span class="text-gray-900 font-bold text-xl">Rp
                        {{ number_format($highlightFee, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Form Pembayaran --}}
            <form id="highlightForm"
                action="{{ route('services.highlight.pay', $service->slug) }}"
                method="POST">
                @csrf

                {{-- START: Pilihan Metode Pembayaran (ACCORDION RADIO BUTTONS) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Metode Pembayaran</label>

                    <div class="space-y-3" id="payment-options-container">
                        @php
                            $bankLogos = [
                                'bca' => ['name' => 'BCA', 'file' => 'bca.png'],
                                'mandiri' => ['name' => 'Mandiri', 'file' => 'mandiri.png'],
                                'bri' => ['name' => 'BRI', 'file' => 'bri.png'],
                                'btn' => ['name' => 'BTN', 'file' => 'btn.png'],
                                'danamon' => ['name' => 'Danamon', 'file' => 'danamon.png'],
                            ];
                            $eWalletLogos = [
                                'ovo' => ['name' => 'OVO', 'file' => 'ovo.png'],
                                'dana' => ['name' => 'DANA', 'file' => 'dana.png'],
                                'gopay' => ['name' => 'GoPay', 'file' => 'gopay.png'],
                            ];
                            // File logo harus ada di path 'public/images/logo_payment/'
                        @endphp

                        {{-- ACCORDION: BANK TRANSFER --}}
                        <div class="accordion-group border border-gray-300 rounded-lg overflow-hidden">
                            <button type="button" class="accordion-header w-full flex items-center justify-between p-3 bg-white hover:bg-gray-50 transition" data-target="bank-transfer-options">
                                <span class="font-bold text-gray-800">Bank Transfer</span>
                                <svg class="h-5 w-5 text-gray-500 transition-transform duration-300 transform" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="bank-transfer-options" class="accordion-content hidden space-y-2 pt-2 pb-2 px-2 border-t border-gray-200 bg-gray-50">
                                @foreach ($bankLogos as $value => $data)
                                    <label for="payment-{{ $value }}"
                                        class="p-3 flex items-center justify-between border border-gray-300 rounded-lg cursor-pointer transition-all hover:border-primary has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:border-primary bg-white">
                                        <div class="flex items-center">
                                            {{-- Ganti path logo sesuai dengan struktur Anda --}}
                                            <img src="{{ asset('images/logo_payment/' . $data['file']) }}"
                                                alt="{{ $data['name'] }} Logo"
                                                class="h-6 w-auto object-contain mr-3">
                                            <span class="font-medium text-gray-800">{{ $data['name'] }}</span>
                                        </div>
                                        <input type="radio"
                                            id="payment-{{ $value }}"
                                            name="payment_method"
                                            value="{{ $value }}"
                                            class="text-primary focus:ring-primary h-4 w-4 border-gray-300"
                                            >
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- ACCORDION: E-WALLET --}}
                        <div class="accordion-group border border-gray-300 rounded-lg overflow-hidden">
                            <button type="button" class="accordion-header w-full flex items-center justify-between p-3 bg-white hover:bg-gray-50 transition" data-target="e-wallet-options">
                                <span class="font-bold text-gray-800">E-Wallet</span>
                                <svg class="h-5 w-5 text-gray-500 transition-transform duration-300 transform" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="e-wallet-options" class="accordion-content hidden space-y-2 pt-2 pb-2 px-2 border-t border-gray-200 bg-gray-50">
                                @foreach ($eWalletLogos as $value => $data)
                                    <label for="payment-{{ $value }}"
                                        class="p-3 flex items-center justify-between border border-gray-300 rounded-lg cursor-pointer transition-all hover:border-primary has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:border-primary bg-white">
                                        <div class="flex items-center">
                                            {{-- Ganti path logo sesuai dengan struktur Anda --}}
                                            <img src="{{ asset('images/logo_payment/' . $data['file']) }}"
                                                alt="{{ $data['name'] }} Logo"
                                                class="h-6 w-auto object-contain mr-3">
                                            <span class="font-medium text-gray-800">{{ $data['name'] }}</span>
                                        </div>
                                        <input type="radio"
                                            id="payment-{{ $value }}"
                                            name="payment_method"
                                            value="{{ $value }}"
                                            class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Opsi Gateway Dummy (Dipisahkan karena bukan Bank/E-Wallet) --}}
                         <label for="payment-dummy"
                                        class="p-3 flex items-center justify-between border border-gray-300 rounded-lg cursor-pointer transition-all hover:border-primary has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:border-primary bg-white">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-800">Gateway Dummy (Simulasi)</span>
                                        </div>
                                        <input type="radio"
                                            id="payment-dummy"
                                            name="payment_method"
                                            value="dummy_gateway"
                                            class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    </label>
                    </div>
                    @error('payment_method')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                {{-- END: Pilihan Metode Pembayaran --}}

                {{-- Petunjuk Bayar --}}
                <div id="paymentInfo"
                    class="mt-2 p-4 bg-gray-100 rounded-lg text-sm text-gray-700">
                    <h3 class="font-bold text-gray-800 mb-2">Petunjuk Cara Bayar</h3>
                    <ol id="instructionList"
                        class="list-decimal list-inside text-gray-700 text-sm">
                        <li>Pilih metode pembayaran di atas untuk melihat langkah-langkah.</li>
                    </ol>
                </div>

                <div class="mt-8 text-center">
                    <button type="submit"
                        id="payButton"
                        class="bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition-colors duration-300 text-lg w-full">
                        Bayar & Pesan
                    </button>
                </div>
            </form>

            {{-- Area loading & sukses --}}
            <div id="loadingBox"
                class="status-box loading hidden mt-6">
                <p class="font-semibold text-orange-700">Memproses pembayaran...</p>
                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-10 w-10 mx-auto my-4">
                </div>
            </div>

            <div id="successBox"
                class="status-box success hidden mt-6">
                <p class="font-semibold text-green-700">Pembayaran berhasil! Highlight telah aktif.</p>
                <a href="{{ route('services.highlight') }}"
                    class="text-primary hover:underline block mt-4 font-medium">
                    ← Kembali ke daftar layanan
                </a>
            </div>
        </div>
    </div>

    {{-- Script untuk update petunjuk pembayaran dan logic accordion --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentOptionsContainer = document.getElementById('payment-options-container');
            const instructionList = document.getElementById('instructionList');
            const accordionHeaders = document.querySelectorAll('.accordion-header');

            const instructions = {
                bca: [
                    "Buka aplikasi BCA Mobile / Livin' BCA.",
                    "Login dengan akun Anda.",
                    "Pilih menu 'Transfer' → 'Rekening BCA'.",
                    "Masukkan nomor rekening: 123-456-789 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran di atas.",
                    "Konfirmasi data, lalu klik 'Kirim'.",
                    "Simpan bukti pembayaran."
                ],
                mandiri: [
                    "Buka aplikasi Mandiri Online / Livin' Mandiri.",
                    "Login dengan akun Anda.",
                    "Pilih menu 'Transfer' → 'Antar Rekening / Bank Lain'.",
                    "Masukkan nomor rekening: 987-654-321 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran.",
                    "Konfirmasi transfer, simpan bukti pembayaran."
                ],
                bri: [
                    "Buka aplikasi BRImo / Internet Banking BRI.",
                    "Login dengan akun Anda.",
                    "Pilih menu 'Transfer' → 'Ke Rekening BRI / Bank Lain'.",
                    "Masukkan nomor rekening: 112-233-445 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran.",
                    "Konfirmasi transfer, simpan bukti pembayaran."
                ],
                btn: [
                    "Buka aplikasi BTN / Internet Banking BTN.",
                    "Login dengan akun Anda.",
                    "Pilih menu 'Transfer' → 'Rekening Bank'.",
                    "Masukkan nomor rekening: 556-677-889 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran.",
                    "Konfirmasi transfer, simpan bukti pembayaran."
                ],
                danamon: [
                    "Buka aplikasi Danamon Online / Internet Banking Danamon.",
                    "Login dengan akun Anda.",
                    "Pilih menu 'Transfer' → 'Rekening Bank'.",
                    "Masukkan nomor rekening: 221-334-556 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran.",
                    "Konfirmasi transfer, simpan bukti pembayaran."
                ],
                ovo: [
                    "Buka aplikasi OVO dan login.",
                    "Pilih menu 'Transfer' → 'OVO / Bank'.",
                    "Masukkan nomor tujuan: 0812-3456-7890 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran.",
                    "Konfirmasi transfer, simpan bukti pembayaran."
                ],
                dana: [
                    "Buka aplikasi DANA dan login.",
                    "Pilih menu 'Kirim / Transfer'.",
                    "Masukkan nomor tujuan: 0812-3456-7890 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran.",
                    "Konfirmasi transfer, simpan bukti pembayaran."
                ],
                gopay: [
                    "Buka aplikasi GoPay / Gojek dan login.",
                    "Pilih menu 'Bayar / Transfer'.",
                    "Masukkan nomor tujuan: 0812-3456-7890 a.n. PT. Contoh.",
                    "Masukkan nominal sesuai total pembayaran.",
                    "Konfirmasi pembayaran, simpan bukti pembayaran."
                ],
                dummy_gateway: [
                    "Simulasi gateway online.",
                    "Klik 'Bayar & Pesan' untuk checkout dummy."
                ]
            };

            function updateInstructions(method) {
                const steps = instructions[method] || ["Pilih metode pembayaran di atas untuk melihat langkah-langkah."];
                instructionList.innerHTML = steps.map(step => `<li>${step}</li>`).join('');
            }

            // --- Logika Accordion ---
            accordionHeaders.forEach(header => {
                header.addEventListener('click', (e) => {
                    e.preventDefault();

                    const targetId = header.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon = header.querySelector('svg');
                    const isOpening = targetContent.classList.contains('hidden');
                    const parentGroup = header.closest('.accordion-group');

                    // Tutup semua akordeon lain, kecuali yang diklik jika sedang dibuka
                    document.querySelectorAll('.accordion-content').forEach(content => {
                        const currentIcon = content.closest('.accordion-group').querySelector('.accordion-header svg');
                        // Hanya tutup jika bukan akordeon yang sama, atau akordeon yang sama dan sedang ditutup
                        if (content.id !== targetId || !isOpening) {
                             content.classList.add('hidden');
                             currentIcon.classList.remove('rotate-180');
                             // Hapus pilihan radio button di accordion yang ditutup
                             if (content.id !== targetId) {
                                content.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
                             }
                        }
                    });

                    // Hapus pilihan pada opsi non-accordion (dummy)
                    document.getElementById('payment-dummy').checked = false;

                    if (isOpening) {
                        // Buka akordeon yang diklik
                        targetContent.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        // Tutup akordeon yang diklik
                        targetContent.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                        // Reset instruksi jika ditutup
                        updateInstructions(null);
                        // Hapus pilihan radio button di accordion yang ditutup
                        targetContent.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
                    }
                });
            });

            // --- Logika Radio Button & Instruksi ---
            paymentOptionsContainer.addEventListener('change', (event) => {
                if (event.target.name === 'payment_method' && event.target.type === 'radio') {
                    // Update instruksi
                    updateInstructions(event.target.value);

                    // Tutup accordion lain jika radio button di accordion A diklik
                    const selectedContent = event.target.closest('.accordion-content');
                    if (selectedContent) {
                        document.querySelectorAll('.accordion-content').forEach(content => {
                            if (content.id !== selectedContent.id) {
                                content.classList.add('hidden');
                                content.closest('.accordion-group').querySelector('.accordion-header svg').classList.remove('rotate-180');
                            }
                        });
                    } else {
                        // Jika opsi dummy diklik, pastikan semua accordion tertutup
                        document.querySelectorAll('.accordion-content').forEach(content => {
                            content.classList.add('hidden');
                            content.closest('.accordion-group').querySelector('.accordion-header svg').classList.remove('rotate-180');
                        });
                    }
                }
            });

            // Inisialisasi: Panggil updateInstructions saat DOM selesai (untuk memastikan petunjuk awal terisi)
            const initialCheckedRadio = paymentOptionsContainer.querySelector('input[name="payment_method"]:checked');
            if (initialCheckedRadio) {
                updateInstructions(initialCheckedRadio.value);
                // Pastikan accordion yang berisi radio button tersebut terbuka saat inisialisasi
                 const initialContent = initialCheckedRadio.closest('.accordion-content');
                 if (initialContent) {
                    initialContent.classList.remove('hidden');
                    initialContent.closest('.accordion-group').querySelector('.accordion-header svg').classList.add('rotate-180');
                 }
            } else {
                updateInstructions(null);
            }
        });
    </script>
</x-app-layout>
