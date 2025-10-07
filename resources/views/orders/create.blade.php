<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        {{-- Tombol Kembali --}}
        <a href="javascript:history.back()"
           class="text-gray-600 hover:text-primary hover:underline font-semibold flex items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 mr-1"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        {{-- Box utama --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Kiri ATAS: Detail Layanan (DITAMBAH: lg:sticky lg:top-8) --}}
                <div id="service-details" class="lg:sticky lg:top-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $service->title }}</h1>
                    <div class="flex items-center mb-4 text-gray-600">
                        <img src="{{ $service->user && $service->user->profile_photo ? asset('storage/' . $service->user->profile_photo) : asset('images/profile-user.png') }}"
                             alt="{{ $service->user->full_name ?? 'N/A' }}"
                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-300 mr-2">
                        <span class="font-medium">{{ $service->user->full_name ?? 'N/A' }}</span>
                    </div>

                    @if ($service->images)
                        <h2 class="text-xl font-semibold mt-6 mb-3 text-gray-800">Galeri Layanan</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach (json_decode($service->images) as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                     alt="Gambar {{ $loop->iteration }}"
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200" />
                            @endforeach
                        </div>
                    @endif

                    <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-800">Deskripsi</h2>
                    <div class="text-gray-700 leading-relaxed prose max-w-none">
                        {!! nl2br(e($service->description)) !!}
                    </div>

                    <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-800">Harga</h2>
                    <p class="text-3xl font-bold text-green-600">Rp <span
                              id="servicePrice">{{ number_format($service->price, 0, ',', '.') }}</span></p>

                    <div class="flex items-center mt-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path
                                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                        <span class="ml-2 text-gray-600 text-sm">({{ number_format($service->avg_rating, 1) }} dari
                            5)</span>
                    </div>
                </div>

                {{-- Kanan BAWAH: Form Pesan & Pembayaran --}}
                <div class="col-span-1 lg:col-start-2 lg:row-start-1 self-start bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Pesan Layanan</h2>
                    <form id="order-form" action="{{ route('orders.store') }}"
                          method="POST">
                        @csrf
                        <input type="hidden"
                               name="service_id"
                               value="{{ $service->id }}" />

                        {{-- HIDDEN INPUTS (Data yang akan disubmit) --}}
                        <input type="hidden" id="hidden_customer_phone" name="customer_phone" value="{{ old('customer_phone', Auth::user()->no_telp ?? '') }}">
                        <input type="hidden" id="hidden_customer_address" name="customer_address" value="{{ old('customer_address', Auth::user()->address ?? '') }}">
                        <input type="hidden" id="hidden_note" name="note" value="">

                        {{-- START: RINGKASAN DATA ORDER DENGAN MODAL --}}
                        <div class="space-y-4 mb-4 p-4 border border-gray-300 rounded-lg bg-white">
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-gray-800">Detail Kontak Order</h3>
                                <button type="button" id="open-modal-btn" class="text-primary text-sm font-semibold hover:underline">
                                    Ubah Detail
                                </button>
                            </div>

                            {{-- Ringkasan Telepon --}}
                            <div class="text-sm text-gray-700 border-b pb-2">
                                <span class="font-semibold block">Nomor Telepon:</span>
                                <span id="summary_phone">{{ Auth::user()->no_telp ?? 'Belum diisi' }}</span>
                            </div>

                            {{-- Ringkasan Alamat --}}
                            <div class="text-sm text-gray-700 border-b pb-2">
                                <span class="font-semibold block">Alamat / Lokasi:</span>
                                <span id="summary_address" class="line-clamp-2">{{ Auth::user()->address ?? 'Belum diisi' }}</span>
                            </div>

                            {{-- Ringkasan Catatan --}}
                            <div class="text-sm text-gray-700">
                                <span class="font-semibold block">Catatan Tambahan:</span>
                                <span id="summary_note" class="line-clamp-2 italic text-gray-500">Tidak ada catatan</span>
                            </div>
                        </div>
                        {{-- END: RINGKASAN DATA ORDER DENGAN MODAL --}}


                        {{-- Pilihan Metode Pembayaran (MENGGUNAKAN ACCORDION RADIO BUTTONS) --}}
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
                                                       required>
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
                            </div>
                            @error('payment_method')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- END: Pilihan Metode Pembayaran --}}

                        {{-- Petunjuk Bayar --}}
                        <div id="payment-instructions"
                             class="bg-gray-100 p-4 rounded-lg mb-4">
                            <h3 class="font-bold text-gray-800 mb-2">Petunjuk Cara Bayar</h3>
                            <ol class="list-decimal list-inside text-gray-700 text-sm"
                                id="instruction-list">
                                <li>Pilih metode pembayaran di atas untuk melihat langkah-langkah.</li>
                            </ol>
                        </div>

                        {{-- Detail Pembayaran --}}
                        <div class="bg-gray-100 p-4 rounded-lg mb-4">
                            <h3 class="font-bold text-gray-800 mb-2">Detail Pembayaran</h3>
                            <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
                                <span>Harga Layanan:</span>
                                <span>Rp <span
                                          id="servicePriceDisplay">{{ number_format($service->price, 0, ',', '.') }}</span></span>
                            </div>
                            <div
                                 class="flex justify-between items-center text-sm text-gray-600 border-b border-gray-300 pb-2">
                                <span>Fee Platform (5%):</span>
                                <span>+ Rp <span id="platformFee">0</span></span>
                            </div>
                            <div class="flex justify-between items-center mt-2 font-bold text-lg text-gray-900">
                                <span>Total Bayar:</span>
                                <span>Rp <span id="totalPrice">0</span></span>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Bayar & Pesan Sekarang
                        </button>

                        {{-- Pernyataan S&K --}}
                        <p class="text-xs text-center text-gray-500 mt-2">
                            Dengan melanjutkan pembayaran, kamu menyetujui **Syarat & Ketentuan Layanan Penyedia Jasa**.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- START: MODAL EDIT DETAIL ORDER --}}
    <div id="edit-detail-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden" aria-modal="true" role="dialog">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 p-6 transform transition-all">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-900">Ubah Detail Kontak & Catatan</h3>
                {{-- Tombol Close (Hanya Ikon X) --}}
                <button type="button" id="close-modal-btn-top" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div>
                {{-- Nomor Telepon --}}
                <div class="mb-4">
                    <label for="modal_customer_phone" class="block text-gray-700 font-semibold mb-1">Nomor Telepon</label>
                    <input type="text"
                           id="modal_customer_phone"
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition"
                           placeholder="Masukkan nomor telepon Anda">
                </div>

                {{-- Alamat --}}
                <div class="mb-4">
                    <label for="modal_customer_address" class="block text-gray-700 font-semibold mb-1">Alamat / Lokasi</label>
                    <textarea id="modal_customer_address"
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition"
                              placeholder="Masukkan alamat lengkap Anda"
                              rows="3"></textarea>
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label for="modal_note" class="block text-gray-700 font-semibold mb-1">Catatan / Instruksi Tambahan</label>
                    <textarea id="modal_note"
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition"
                              placeholder="Contoh: jam layanan, instruksi khusus"
                              rows="3"></textarea>
                </div>

                <div class="flex justify-end mt-4">
                    {{-- Tombol Simpan --}}
                    <button type="button" id="save-modal-btn" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: MODAL EDIT DETAIL ORDER --}}


    <script>
        // Hitung total harga otomatis (Kode ini tidak berubah)
        const priceEl = document.getElementById('servicePriceDisplay');
        const feeEl = document.getElementById('platformFee');
        const totalEl = document.getElementById('totalPrice');

        const basePrice = parseInt(
            priceEl.textContent.replace(/\./g, '')
        );

        const fee = Math.round(basePrice * 0.05);

        const total = basePrice + fee;

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        feeEl.textContent = formatRupiah(fee);
        totalEl.textContent = formatRupiah(total);

        document.addEventListener('DOMContentLoaded', () => {
            const paymentOptionsContainer = document.getElementById('payment-options-container');
            const instructionList = document.getElementById('instruction-list');
            const accordionHeaders = document.querySelectorAll('.accordion-header');

            // --- ELEMEN MODAL ---
            const modal = document.getElementById('edit-detail-modal');
            const openModalBtn = document.getElementById('open-modal-btn');
            const closeModalBtnTop = document.getElementById('close-modal-btn-top'); // Tombol X
            const saveModalBtn = document.getElementById('save-modal-btn'); // Tombol Simpan

            // Input fields di dalam modal
            const modalPhone = document.getElementById('modal_customer_phone');
            const modalAddress = document.getElementById('modal_customer_address');
            const modalNote = document.getElementById('modal_note');

            // Input fields tersembunyi (untuk submit form)
            const hiddenPhone = document.getElementById('hidden_customer_phone');
            const hiddenAddress = document.getElementById('hidden_customer_address');
            const hiddenNote = document.getElementById('hidden_note');

            // Ringkasan tampilan
            const summaryPhone = document.getElementById('summary_phone');
            const summaryAddress = document.getElementById('summary_address');
            const summaryNote = document.getElementById('summary_note');

            // Update ringkasan awal
            function updateSummary(phone, address, note) {
                summaryPhone.textContent = phone || 'Belum diisi';
                summaryAddress.textContent = address || 'Belum diisi';
                summaryNote.textContent = note || 'Tidak ada catatan';

                // Styling untuk Catatan
                if (!note) {
                     summaryNote.classList.add('italic', 'text-gray-500');
                } else {
                     summaryNote.classList.remove('italic', 'text-gray-500');
                }

                // Styling tombol 'Ubah Detail' jika data penting kosong
                 if (!phone || !address) {
                    document.getElementById('open-modal-btn').textContent = 'Lengkapi Detail';
                    document.getElementById('open-modal-btn').classList.add('text-red-500');

                 } else {
                    document.getElementById('open-modal-btn').textContent = 'Ubah Detail';
                    document.getElementById('open-modal-btn').classList.remove('text-red-500');
                 }
            }
            // Panggil ini saat DOM load
            updateSummary(hiddenPhone.value, hiddenAddress.value, hiddenNote.value);


            // --- FUNGSI MODAL ---
            const closeModal = () => {
                modal.classList.add('hidden');
            };

            const openModal = () => {
                // Salin nilai dari hidden input ke modal saat dibuka
                modalPhone.value = hiddenPhone.value;
                modalAddress.value = hiddenAddress.value;
                modalNote.value = hiddenNote.value;

                modal.classList.remove('hidden');
            };

            openModalBtn.addEventListener('click', openModal);
            closeModalBtnTop.addEventListener('click', closeModal); // Tombol X

            // FUNGSI SAVE (Sudah Dipastikan Benar)
            saveModalBtn.addEventListener('click', () => {
                // 1. Simpan nilai dari modal ke hidden input
                hiddenPhone.value = modalPhone.value.trim();
                hiddenAddress.value = modalAddress.value.trim();
                hiddenNote.value = modalNote.value.trim();

                // 2. Update tampilan ringkasan
                updateSummary(hiddenPhone.value, hiddenAddress.value, hiddenNote.value);

                // 3. Tutup modal
                closeModal();
            });


            // --- FUNGSI ACCORDION DAN INTRUKSI ---

            accordionHeaders.forEach(header => {
                header.addEventListener('click', (e) => {
                    e.preventDefault();

                    const targetId = header.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon = header.querySelector('svg');
                    const isOpening = targetContent.classList.contains('hidden');

                    // Tutup semua akordeon lain
                    document.querySelectorAll('.accordion-content').forEach(content => {
                        if (content.id !== targetId) {
                            content.classList.add('hidden');
                            content.closest('.accordion-group').querySelector('.accordion-header svg').classList.remove('rotate-180');
                            // Hapus pilihan radio button di accordion yang ditutup
                            content.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
                        }
                    });

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
                        // Hapus pilihan radio button
                        targetContent.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
                    }
                });
            });


            function updateInstructions(method) {
                let steps = [];

                switch (method) {
                    case 'bca':
                        steps = [
                            "Buka aplikasi BCA Mobile / Livin' BCA.",
                            "Login dengan akun Anda.",
                            "Pilih menu 'Transfer' → 'Rekening BCA'.",
                            "Masukkan nomor rekening: 1234567890 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran di atas.",
                            "Konfirmasi data, lalu klik 'Kirim'.",
                            "Simpan bukti pembayaran."
                        ];
                        break;
                    case 'mandiri':
                        steps = [
                            "Buka aplikasi Mandiri Online / Livin' Mandiri.",
                            "Login dengan akun Anda.",
                            "Pilih menu 'Transfer' → 'Antar Rekening / Bank Lain'.",
                            "Masukkan nomor rekening: 9876543210 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran.",
                            "Konfirmasi transfer, simpan bukti pembayaran."
                        ];
                        break;
                    case 'bri':
                        steps = [
                            "Buka aplikasi BRImo / Internet Banking BRI.",
                            "Login dengan akun Anda.",
                            "Pilih menu 'Transfer' → 'Ke Rekening BRI / Bank Lain'.",
                            "Masukkan nomor rekening: 1122334455 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran.",
                            "Konfirmasi transfer, simpan bukti pembayaran."
                        ];
                        break;
                    case 'btn':
                        steps = [
                            "Buka aplikasi BTN / Internet Banking BTN.",
                            "Login dengan akun Anda.",
                            "Pilih menu 'Transfer' → 'Rekening Bank'.",
                            "Masukkan nomor rekening: 5566778899 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran.",
                            "Konfirmasi transfer, simpan bukti pembayaran."
                        ];
                        break;
                    case 'danamon':
                        steps = [
                            "Buka aplikasi Danamon Online / Internet Banking Danamon.",
                            "Login dengan akun Anda.",
                            "Pilih menu 'Transfer' → 'Transfer ke Rekening'.",
                            "Masukkan nomor rekening: 2213345566 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran.",
                            "Konfirmasi transfer, simpan bukti pembayaran."
                        ];
                        break;
                    case 'ovo':
                        steps = [
                            "Buka aplikasi OVO dan login.",
                            "Pilih menu 'Transfer' → 'OVO / Bank'.",
                            "Masukkan nomor tujuan: 081234567890 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran.",
                            "Konfirmasi transfer, simpan bukti pembayaran."
                        ];
                        break;
                    case 'dana':
                        steps = [
                            "Buka aplikasi DANA dan login.",
                            "Pilih menu 'Kirim / Transfer'.",
                            "Masukkan nomor tujuan: 081234567890 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran.",
                            "Konfirmasi transfer, simpan bukti pembayaran."
                        ];
                        break;
                    case 'gopay':
                        steps = [
                            "Buka aplikasi GoPay / Gojek dan login.",
                            "Pilih menu 'Bayar / Transfer'.",
                            "Masukkan nomor tujuan: 081234567890 a.n. Lomba Service.",
                            "Masukkan nominal sesuai total pembayaran.",
                            "Konfirmasi pembayaran, simpan bukti pembayaran."
                        ];
                        break;
                    default:
                        steps = ["Pilih metode pembayaran di atas untuk melihat langkah-langkah."];
                }

                instructionList.innerHTML = steps.map(s => `<li>${s}</li>`).join('');
            }

            paymentOptionsContainer.addEventListener('change', (event) => {
                if (event.target.name === 'payment_method' && event.target.type === 'radio') {
                    // Pastikan akordeon yang sesuai terbuka saat radio button diklik
                    const accordionContent = event.target.closest('.accordion-content');
                    if (accordionContent && accordionContent.classList.contains('hidden')) {
                        accordionContent.classList.remove('hidden');
                        accordionContent.closest('.accordion-group').querySelector('.accordion-header svg').classList.add('rotate-180');
                    }
                    updateInstructions(event.target.value);
                }
            });

            const initialCheckedRadio = paymentOptionsContainer.querySelector('input[name="payment_method"]:checked');
            if (initialCheckedRadio) {
                updateInstructions(initialCheckedRadio.value);
            } else {
                updateInstructions(null);
            }
        });
    </script>
</x-app-layout>
