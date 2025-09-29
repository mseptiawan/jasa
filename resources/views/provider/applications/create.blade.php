<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Daftar sebagai Penyedia Jasa</h1>
            <p class="text-gray-600 mb-6">Lengkapi formulir di bawah ini untuk mendaftar menjadi penyedia jasa.</p>

            {{-- Pesan status atau info --}}
            @if(session('status') || isset($status))
                <div class="mb-6 p-4 bg-blue-100 text-blue-700 rounded-lg border border-blue-200">
                    <p>{{ session('status') ?? $status }}</p>
                </div>
            @endif

            {{-- Form hanya tampil kalau user belum daftar --}}
            @if(!session('status') && !isset($status))
                <form method="POST" action="{{ route('service.apply.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-5">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                            placeholder="Contoh: 081234567890">
                        @error('phone_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                            placeholder="Contoh: Jl. Sudirman No. 123, Jakarta">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="id_card" class="block text-sm font-medium text-gray-700 mb-1">Upload KTP / SIM</label>
                            <input type="file" name="id_card" id="id_card"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            @error('id_card')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <img id="id_card_preview" src="#" alt="Pratinjau KTP" class="mt-4 hidden w-full h-auto rounded-lg border border-gray-300">
                        </div>

                        <div>
                            <label for="selfie" class="block text-sm font-medium text-gray-700 mb-1">Upload Selfie dengan KTP</label>
                            <input type="file" name="selfie" id="selfie"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            @error('selfie')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <img id="selfie_preview" src="#" alt="Pratinjau Selfie" class="mt-4 hidden w-full h-auto rounded-lg border border-gray-300">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skill Utama</label>
                        <input type="text" name="skills" id="skills" value="{{ old('skills') }}"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                            placeholder="Contoh: Pemasangan CCTV, Jasa Desain Grafis">
                        @error('skills')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Pengalaman Kerja</label>
                        <select name="experience" id="experience"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
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
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
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
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        @error('cv')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="portfolio" class="block text-sm font-medium text-gray-700 mb-1">Link website Portofolio</label>
                        <input type="text" name="portfolio" id="portfolio" value="{{ old('portfolio') }}"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idCardInput = document.getElementById('id_card');
            const idCardPreview = document.getElementById('id_card_preview');
            const selfieInput = document.getElementById('selfie');
            const selfiePreview = document.getElementById('selfie_preview');

            function setupPreview(inputElement, previewElement) {
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
        });
    </script>
</x-app-layout>
