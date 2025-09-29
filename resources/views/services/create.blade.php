<x-app-layout>
    {{-- CSS Kustom & Font Montserrat --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        .text-primary {
            color: #2b3cd7;
        }

        /* Styling untuk input yang lebih bersih */
        .modern-input {
            border: 1px solid #e2e8f0;
            padding: 1rem;
            border-radius: 0.5rem;
            width: 100%;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .modern-input:focus {
            outline: none;
            border-color: #2b3cd7;
            box-shadow: 0 0 0 3px rgba(43, 60, 215, 0.1);
        }

        /* Styling untuk Select2 agar konsisten */
        .select2-container .select2-selection--single {
            height: 58px !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem !important;
            background-color: #fff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2.25rem !important;
            padding-left: 0 !important;
            color: #4b5563;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
            right: 1rem !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            outline: none;
            border-color: #2b3cd7 !important;
            box-shadow: 0 0 0 3px rgba(43, 60, 215, 0.1) !important;
        }

        /* Menyesuaikan jarak agar konten tidak tertutup navbar fixed */
        .content-container {
            margin-top: 5rem;
        }

        /* Styling untuk preview gambar */
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Perbaikan untuk masalah z-index peta */
        #map {
            z-index: 1;
        }

        .main-form-box {
            position: relative;
            z-index: 10;
        }
    </style>

    <div class="container mx-auto p-4 md:p-8 max-w-4xl content-container">
        {{-- Kembali ke Halaman Sebelumnya --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-primary transition-colors flex items-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Satu Box Utama yang Menampung Semua Formulir --}}
        <div class="bg-white p-8 rounded-2xl border border-gray-200 main-form-box">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-900">Tambah Layanan Baru</h1>

            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Bagian Detail Utama --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Detail Layanan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Judul Layanan</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="modern-input @error('title') border-red-500 @enderror" placeholder="Contoh: Jasa Desain Grafis Profesional">
                            @error('title')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-700">Harga (Rp)</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                class="modern-input @error('price') border-red-500 @enderror" placeholder="Contoh: 500000" min="0">
                            @error('price')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="5"
                            class="modern-input @error('description') border-red-500 @enderror" placeholder="Jelaskan secara rinci layanan yang Anda tawarkan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="subcategory_id" class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                        <select name="subcategory_id" id="subcategory_id"
                            class="modern-input @error('subcategory_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Bagian Spesifikasi Tambahan --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Spesifikasi Tambahan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="job_type" class="block mb-2 text-sm font-medium text-gray-700">Jenis Pekerjaan</label>
                            <select name="job_type" id="job_type"
                                class="modern-input modern-select @error('job_type') border-red-500 @enderror">
                                <option value="">Pilih Jenis</option>
                                <option value="Full Time" {{ old('job_type') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time" {{ old('job_type') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Freelance" {{ old('job_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                            @error('job_type')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="experience" class="block mb-2 text-sm font-medium text-gray-700">Pengalaman</label>
                            <select name="experience" id="experience"
                                class="modern-input modern-select @error('experience') border-red-500 @enderror">
                                <option value="">Pilih Pengalaman</option>
                                <option value="0-1 Tahun" {{ old('experience') == '0-1 Tahun' ? 'selected' : '' }}>0-1 Tahun</option>
                                <option value="1-3 Tahun" {{ old('experience') == '1-3 Tahun' ? 'selected' : '' }}>1-3 Tahun</option>
                                <option value="3-5 Tahun" {{ old('experience') == '3-5 Tahun' ? 'selected' : '' }}>3-5 Tahun</option>
                                <option value=">5 Tahun" {{ old('experience') == '>5 Tahun' ? 'selected' : '' }}>&gt;5 Tahun</option>
                            </select>
                            @error('experience')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="industry" class="block mb-2 text-sm font-medium text-gray-700">Industri</label>
                            <select name="industry" id="industry"
                                class="modern-input modern-select @error('industry') border-red-500 @enderror">
                                <option value="">Pilih Industri</option>
                                <option value="IT" {{ old('industry') == 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="Kesehatan" {{ old('industry') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Pendidikan" {{ old('industry') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Jasa" {{ old('industry') == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                <option value="Lainnya" {{ old('industry') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('industry')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Bagian Lokasi --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Lokasi</h2>
                    <div class="form-group">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Pilih Lokasi (Klik di peta atau Cari)
                        </label>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                        <div id="map" class="w-full h-80 rounded-lg border border-gray-300 mb-2"></div>
                        @error('latitude')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('longitude')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                            class="modern-input @error('address') border-red-500 @enderror" placeholder="Tuliskan alamat lengkap... (Opsional)">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Bagian Kontak & Gambar --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Kontak & Media</h2>
                    <div class="form-group">
                        <label for="contact" class="block mb-2 text-sm font-medium text-gray-700">Kontak</label>
                        <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                            class="modern-input @error('contact') border-red-500 @enderror"
                            placeholder="0812xxxxxxx atau email@example.com">
                        @error('contact')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bagian Upload Gambar dengan Preview --}}
                    <div class="form-group">
                        <label for="images" class="block mb-2 text-sm font-medium text-gray-700">Upload Gambar (boleh lebih dari 1)</label>
                        <input type="file" name="images[]" id="images" multiple
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer @error('images') border-red-500 @enderror">
                        @error('images')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        {{-- Container untuk preview gambar --}}
                        <div id="image-preview-container" class="mt-4 flex flex-wrap gap-2"></div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="bg-gray-900 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors duration-300 text-lg">
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script untuk Leaflet Map & Select2 --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    {{-- Script jQuery dan Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#subcategory_id').select2({
                placeholder: "Pilih Kategori",
                allowClear: true
            });

            // Leaflet Map Initialization
            var map = L.map('map').setView([-2.9761, 104.7754], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var marker;
            var oldLat = parseFloat("{{ old('latitude') }}");
            var oldLng = parseFloat("{{ old('longitude') }}");

            if (!isNaN(oldLat) && !isNaN(oldLng)) {
                marker = L.marker([oldLat, oldLng]).addTo(map);
                map.setView([oldLat, oldLng], 15);
            }

            map.on('click', function (e) {
                var latlng = e.latlng;
                if (marker) {
                    marker.setLatLng(latlng);
                } else {
                    marker = L.marker(latlng).addTo(map);
                }
                document.getElementById('latitude').value = latlng.lat;
                document.getElementById('longitude').value = latlng.lng;
            });

            var geocoder = L.Control.geocoder({
                defaultMarkGeocode: false,
                collapsed: true,
                placeholder: 'Cari lokasi...',
            }).addTo(map);

            geocoder.on('markgeocode', function (e) {
                var center = e.geocode.center;
                if (marker) {
                    marker.setLatLng(center);
                } else {
                    marker = L.marker(center).addTo(map);
                }
                map.setView(center, 15);
                document.getElementById('latitude').value = center.lat;
                document.getElementById('longitude').value = center.lng;
            });

            // Image Preview Functionality (Modifikasi)
            const imagesInput = document.getElementById('images');
            const imagePreviewContainer = document.getElementById('image-preview-container');

            let selectedFiles = [];

            imagesInput.addEventListener('change', function(event) {
                selectedFiles.push(...Array.from(event.target.files));
                updatePreview();
            });

            function updatePreview() {
                imagePreviewContainer.innerHTML = ''; // Hapus semua pratinjau yang ada

                // Gunakan DataTransfer untuk membuat objek file yang bisa dikirim
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                imagesInput.files = dataTransfer.files;

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.classList.add('relative');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('image-preview');
                        img.classList.add('mb-2');

                        const deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        `;
                        deleteBtn.classList.add('absolute', 'top-1', 'right-1', 'bg-red-500', 'rounded-full', 'p-1', 'hover:bg-red-600', 'transition');
                        deleteBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            selectedFiles.splice(index, 1);
                            updatePreview();
                        });

                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(deleteBtn);
                        imagePreviewContainer.appendChild(imgWrapper);
                    };

                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
</x-app-layout>
