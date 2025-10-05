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
                {{-- Kiri: Detail Layanan --}}
                <div>
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
                    <p class="text-gray-700 leading-relaxed">
                        {{ $service->description }}
                    </p>

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

                {{-- Kanan: Form Pesan & Pembayaran --}}
                <div class="lg:sticky lg:top-8 self-start bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Pesan Layanan</h2>
                    <form action="{{ route('orders.store') }}"
                          method="POST">
                        @csrf
                        <input type="hidden"
                               name="service_id"
                               value="{{ $service->id }}" />

                        {{-- Nomor Telepon --}}
                        <div class="mb-4">
                            <label for="customer_phone"
                                   class="block text-gray-700 font-semibold mb-1">Nomor Telepon</label>
                            <input type="text"
                                   id="customer_phone"
                                   name="customer_phone"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   placeholder="Masukkan nomor telepon Anda"
                                   value="{{ old('customer_phone', Auth::user()->no_telp ?? '') }}">
                            @error('customer_phone')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-4">
                            <label for="customer_address"
                                   class="block text-gray-700 font-semibold mb-1">Alamat / Lokasi</label>
                            <textarea id="customer_address"
                                      name="customer_address"
                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                      placeholder="Masukkan alamat lengkap Anda"
                                      rows="3">{{ old('customer_address', Auth::user()->address ?? '') }}</textarea>
                            @error('customer_address')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-4">
                            <label for="note"
                                   class="block text-gray-700 font-semibold mb-1">Catatan / Instruksi Tambahan</label>
                            <textarea id="note"
                                      name="note"
                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                      placeholder="Contoh: jam layanan, instruksi khusus"
                                      rows="3"></textarea>
                            @error('note')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pilihan Metode Pembayaran --}}
                        <div class="mb-4">
                            <label for="payment_method"
                                   class="block text-gray-700 font-semibold mb-1">Metode Pembayaran</label>
                            <select id="payment_method"
                                    name="payment_method"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                                <optgroup label="Bank Transfer">
                                    <option value="bca">BCA</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="bri">BRI</option>
                                    <option value="btn">BTN</option>
                                    <option value="danamon">Danamon</option>
                                </optgroup>
                                <optgroup label="E-Wallet">
                                    <option value="ovo">OVO</option>
                                    <option value="dana">DANA</option>
                                    <option value="gopay">GoPay</option>
                                </optgroup>
                            </select>
                        </div>

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

                        {{-- Submit --}}
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paymentMethodEl = document.getElementById('payment_method');
            const instructionList = document.getElementById('instruction-list');

            function updateInstructions() {
                const method = paymentMethodEl.value;
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
                        steps = ["Pilih metode pembayaran untuk melihat langkah-langkah."];
                }

                instructionList.innerHTML = steps.map(s => `<li>${s}</li>`).join('');
            }

            paymentMethodEl.addEventListener('change', updateInstructions);
            updateInstructions();


        });
    </script>

</x-app-layout>
