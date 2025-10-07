<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Daftar sebagai Penyedia Jasa</h1>
            <p class="text-gray-600 mb-6">Lengkapi formulir di bawah ini untuk mendaftar menjadi penyedia jasa.</p>

            {{-- START: INDIKATOR TAHAP (STEPPER) --}}
            @php
                // Logika: 1 = Baca Persyaratan (Awal), 2 = Isi Formulir, 3 = Menunggu Persetujuan
                $hasStatus = session('status') || isset($status);
                // Jika sudah ada status, anggap di tahap 3. Jika tidak, set ke tahap 1 secara default.
                $initialStep = $hasStatus ? 3 : 1;
                $currentStep = $initialStep;
            @endphp

            <div class="mb-8 flex items-center justify-between space-x-2 lg:space-x-8" id="stepper-indicator">
                {{-- Helper class untuk Stepper --}}
                @php
                    $stepClasses = 'flex-1 border-t-2 pt-2 text-center text-sm font-medium';
                    $activeClasses = 'border-blue-600 text-blue-600';
                    $completedClasses = 'border-green-500 text-green-600';
                    $defaultClasses = 'border-gray-200 text-gray-500';
                @endphp

                {{-- Tahap 1: Baca Persyaratan --}}
                <div id="step-1-indicator" class="{{ $stepClasses }} {{ $currentStep == 1 ? $activeClasses : ($currentStep > 1 ? $completedClasses : $defaultClasses) }}">
                    <span class="block">1. Baca Persyaratan</span>
                </div>

                {{-- Tahap 2: Isi Formulir --}}
                <div id="step-2-indicator" class="{{ $stepClasses }} {{ $currentStep == 2 ? $activeClasses : ($currentStep > 2 ? $completedClasses : $defaultClasses) }}">
                    <span class="block">2. Isi Formulir</span>
                </div>

                {{-- Tahap 3: Menunggu Persetujuan --}}
                <div id="step-3-indicator" class="{{ $stepClasses }} {{ $currentStep == 3 ? $activeClasses : $defaultClasses }}">
                    <span class="block">3. Menunggu Persetujuan</span>
                </div>
            </div>
            {{-- END: INDIKATOR TAHAP --}}

            {{-- Konten Utama (Form, Persyaratan, atau Status) --}}

            @if($hasStatus)
                <div class="mt-8 p-6 bg-blue-50 text-blue-800 rounded-lg border border-blue-200 text-center" id="status-content">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto mb-4 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">Pengajuan Anda Sedang Diproses!</h3>
                    <p class="text-lg mb-4">{{ session('status') ?? $status }}</p>
                    <p class="text-gray-600">Kami akan memberikan kabar secepatnya melalui email dan notifikasi Anda.</p>
                </div>

            @else
                <div id="step-1-content" class="{{ $initialStep == 1 ? '' : 'hidden' }}">
                    <h2 class="text-xl font-semibold mb-4">Persyaratan Menjadi Penyedia Jasa</h2>

                    <div id="syarat-scroll-area" class="p-4 bg-gray-50 border border-gray-200 h-64 overflow-y-scroll text-sm text-gray-700 leading-relaxed rounded-lg mb-6 transition duration-300">
                        <p class="font-bold mb-2">1. Ketentuan Umum</p>
                        <ul class="list-disc list-inside ml-4 mb-4">
                            <li>Wajib memiliki identitas diri (KTP/SIM) yang sah.</li>
                            <li>Usia minimal 18 tahun.</li>
                            <li>Bertanggung jawab penuh atas kualitas layanan yang diberikan.</li>
                            <li>Tidak terlibat dalam aktivitas ilegal atau merugikan pengguna lain.</li>
                        </ul>

                        <p class="font-bold mb-2">2. Dokumen yang Diperlukan</p>
                        <ul class="list-disc list-inside ml-4 mb-4">
                            <li>Salinan KTP/SIM yang jelas.</li>
                            <li>Foto *selfie* sambil memegang KTP/SIM.</li>
                            <li>CV atau daftar pengalaman kerja terbaru (PDF/DOCX).</li>
                            <li>Bukti keahlian/sertifikasi (jika ada).</li>
                        </ul>

                        <p class="font-bold mb-2">3. Standar Kualitas Layanan</p>
                        <p>Penyedia Jasa wajib memberikan layanan dengan standar profesionalisme tinggi. Hal ini termasuk ketepatan waktu, komunikasi yang jelas, dan penyelesaian pekerjaan sesuai kesepakatan. Setiap keluhan pelanggan akan diproses dan dapat memengaruhi status keanggotaan Anda.</p>

                        <p class="font-bold mt-4 mb-2">4. Pembayaran dan Komisi</p>
                        <p>Semua transaksi akan dilakukan melalui platform. Platform berhak memotong komisi sebesar **10%** dari total biaya jasa yang disepakati. Pembayaran akan ditransfer ke rekening Penyedia Jasa setiap hari Jumat.</p>

                        <p class="mt-4 text-red-600 font-semibold">PASTIKAN Anda membaca seluruh ketentuan di atas. Tombol persetujuan akan aktif setelah Anda menggulir (scroll) hingga akhir.</p>
                        <div class="h-10"></div>
                        <p class="text-xs text-gray-500 text-center">**Akhir dari Persyaratan**</p>
                    </div>

                    <div class="mb-4 flex items-center">
                        <input type="checkbox" id="agree-checkbox" disabled class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="agree-checkbox" class="ml-2 block text-sm text-gray-900">
                            Saya telah membaca dan menyetujui seluruh Persyaratan dan Ketentuan di atas.
                        </label>
                    </div>

                    <button type="button" id="next-to-form-btn" disabled
                        class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-400 text-white font-bold rounded-lg cursor-not-allowed transition-all duration-200">
                        Lanjut ke Isi Formulir
                    </button>
                </div>

                <form method="POST" action="{{ route('service.apply.submit') }}" enctype="multipart/form-data" id="step-2-content" class="hidden">
                    @csrf

                    <div class="mb-5">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Contoh: 081234567890">
                        @error('phone_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Contoh: Jl. Sudirman No. 123, Jakarta">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="id_card" class="block text-sm font-medium text-gray-700 mb-1">Upload KTP / SIM</label>
                            <input type="file" name="id_card" id="id_card"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            @error('id_card')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <img id="id_card_preview" src="#" alt="Pratinjau KTP" class="mt-4 hidden w-full h-auto rounded-lg border border-gray-300">
                        </div>

                        <div>
                            <label for="selfie" class="block text-sm font-medium text-gray-700 mb-1">Upload Selfie dengan KTP</label>
                            <input type="file" name="selfie" id="selfie"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            @error('selfie')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <img id="selfie_preview" src="#" alt="Pratinjau Selfie" class="mt-4 hidden w-full h-auto rounded-lg border border-gray-300">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skill Utama</label>
                        <input type="text" name="skills" id="skills" value="{{ old('skills') }}"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Contoh: Pemasangan CCTV, Jasa Desain Grafis">
                        @error('skills')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Pengalaman Kerja</label>
                        <select name="experience" id="experience"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">-- Pilih Pengalaman --</option>
                            <option value="0-1 tahun" {{ old('experience') == '0-1 tahun' ? 'selected' : '' }}>0-1 tahun</option>
                            <option value="1-3 tahun" {{ old('experience') == '1-3 tahun' ? 'selected' : '' }}>1-3 tahun</option>
                            <option value="3-5 tahun" {{ old('experience') == '3-5 tahun' ? 'selected' : '' }}>3-5 tahun</option>
                            <option value="5+ tahun" {{ old('experience') == '5+ tahun' ? 'selected' : '' }}>5+ tahun</option>
                        </select>
                        @error('experience')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="education" class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                        <select name="education" id="education"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">-- Pilih Pendidikan --</option>
                            <option value="SD" {{ old('education') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('education') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA/SMK" {{ old('education') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                            <option value="D3" {{ old('education') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('education') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('education') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('education') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('education')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="cv" class="block text-sm font-medium text-gray-700 mb-1">Upload CV (PDF/DOCX)</label>
                        <input type="file" name="cv" id="cv"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        @error('cv')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="portfolio" class="block text-sm font-medium text-gray-700 mb-1">Link website Portofolio</label>
                        <input type="text" name="portfolio" id="portfolio" value="{{ old('portfolio') }}"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Opsional">
                        @error('portfolio')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-black text-white font-bold rounded-lg hover:bg-gray-800 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                            </svg>
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-w-sm w-full mx-4 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            <div class="p-6 bg-blue-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                            Persyaratan Selesai Dibaca
                        </h3>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-500 mb-4">
                    Anda telah menggulir (scroll) hingga akhir dari dokumen Persyaratan dan Ketentuan Penyedia Jasa.
                </p>
                <p class="text-sm text-gray-700 font-medium">
                    **Silakan centang kotak persetujuan** di bawah area scroll untuk mengaktifkan tombol **"Lanjut ke Isi Formulir"**.
                </p>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end">
                <button type="button" id="close-modal-btn" class="inline-flex justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Paham
                </button>
            </div>
        </div>
    </div>
    {{-- Script untuk Preview Gambar dan Logika Stepper Tahap 1 & 2 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Elemen
            const idCardInput = document.getElementById('id_card');
            const idCardPreview = document.getElementById('id_card_preview');
            const selfieInput = document.getElementById('selfie');
            const selfiePreview = document.getElementById('selfie_preview');

            const scrollArea = document.getElementById('syarat-scroll-area');
            const agreeCheckbox = document.getElementById('agree-checkbox');
            const nextButton = document.getElementById('next-to-form-btn');
            const step1Content = document.getElementById('step-1-content');
            const step2Content = document.getElementById('step-2-content');
            const step1Indicator = document.getElementById('step-1-indicator');
            const step2Indicator = document.getElementById('step-2-indicator');

            // Elemen Modal
            const confirmationModal = document.getElementById('confirmation-modal');
            const closeModalButton = document.getElementById('close-modal-btn');


            // Fungsi Preview Gambar
            function setupPreview(inputElement, previewElement) {
                if (!inputElement || !previewElement) return;

                inputElement.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewElement.src = e.target.result;
                            previewElement.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewElement.classList.add('hidden');
                        previewElement.src = '#';
                    }
                });
            }

            setupPreview(idCardInput, idCardPreview);
            setupPreview(selfieInput, selfiePreview);

            // Logika Stepper untuk Tahap 1 (Baca Persyaratan)
            if (scrollArea && agreeCheckbox && nextButton) {
                let scrolledToEnd = false;

                // 1. Cek status scroll
                scrollArea.addEventListener('scroll', function() {
                    const isEnd = (scrollArea.scrollTop + scrollArea.clientHeight) >= scrollArea.scrollHeight - 1;

                    if (isEnd && !scrolledToEnd) {
                        scrolledToEnd = true;
                        agreeCheckbox.disabled = false;
                        scrollArea.classList.remove('border-gray-200');
                        scrollArea.classList.add('border-green-500');

                        confirmationModal.classList.remove('hidden');
                    }
                });

                // 2. Tutup Modal
                if (closeModalButton) {
                    closeModalButton.addEventListener('click', function() {
                        confirmationModal.classList.add('hidden');
                    });
                }

                // 3. Cek status checkbox
                agreeCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        nextButton.disabled = false;
                        nextButton.classList.add('bg-black', 'hover:bg-gray-800');
                        nextButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    } else {
                        nextButton.disabled = true;
                        nextButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                        nextButton.classList.remove('bg-black', 'hover:bg-gray-800');
                    }
                });

                // 4. Tombol Lanjut ke Tahap 2 (Bagian yang memastikan indikator aktif)
                nextButton.addEventListener('click', function() {
                    // Sembunyikan Tahap 1 dan Tampilkan Tahap 2
                    step1Content.classList.add('hidden');
                    step2Content.classList.remove('hidden');

                    // Update Stepper
                    const completedClasses = 'border-green-500 text-green-600';
                    const activeClasses = 'border-blue-600 text-blue-600';
                    const defaultClasses = 'border-gray-200 text-gray-500'; // Define default classes here for removal

                    // Update Tahap 1 menjadi Selesai (Completed - Green)
                    step1Indicator.classList.remove(activeClasses, defaultClasses);
                    step1Indicator.classList.add(completedClasses);

                    // Update Tahap 2 menjadi Aktif (Active - Blue)
                    // Hapus semua kelas default/completed/active yang mungkin ada
                    step2Indicator.classList.remove(defaultClasses, completedClasses);
                    // Tambahkan kelas active
                    step2Indicator.classList.add(activeClasses);
                });
            }
        });
    </script>
</x-app-layout>
