<x-app-layout>
    {{-- CSS Kustom & Font Montserrat --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          rel="stylesheet" />
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        .text-primary {
            color: #2b3cd7;
        }

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

        .content-container {
            margin-top: 5rem;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }

        #map {
            z-index: 1;
        }

        .main-form-box {
            position: relative;
            z-index: 10;
        }
    </style>

    <div class="container mx-auto p-4 md:p-8 max-w-4xl content-container">
        <div class="mb-6">
            <a href="{{ url()->previous() }}"
               class="text-gray-500 hover:text-primary transition-colors flex items-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-gray-200 main-form-box">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-900">Tambah Layanan Baru</h1>

            <form action="{{ route('services.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-8">
                @csrf

                {{-- DETAIL LAYANAN --}}
                <div class="space-y-6">
                    <div>
                        <label for="images"
                               class="block mb-2 text-sm font-medium text-gray-700">Upload Gambar</label>
                        <input type="file"
                               name="images[]"
                               id="images"
                               multiple
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <div id="image-preview-container"
                             class="mt-4 flex flex-wrap gap-3"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="title"
                                   class="block mb-2 text-sm font-medium text-gray-700">Judul Layanan</label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   value="{{ old('title') }}"
                                   class="modern-input @error('title') border-red-500 @enderror"
                                   placeholder="Contoh: Jasa Desain Grafis Profesional">
                            @error('title')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="price"
                                   class="block mb-2 text-sm font-medium text-gray-700">Harga (Rp)</label>
                            <input type="number"
                                   name="price"
                                   id="price"
                                   value="{{ old('price') }}"
                                   class="modern-input @error('price') border-red-500 @enderror"
                                   placeholder="Contoh: 500000"
                                   min="0">
                            @error('price')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="discount_price"
                                   class="block mb-2 text-sm font-medium text-gray-700">Harga Diskon (Rp)</label>
                            <input type="number"
                                   name="discount_price"
                                   id="discount_price"
                                   value="{{ old('discount_price') }}"
                                   class="modern-input @error('discount_price') border-red-500 @enderror"
                                   placeholder="Opsional: 450000"
                                   min="0">
                            @error('discount_price')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="service_type"
                                   class="block mb-2 text-sm font-medium text-gray-700">Tipe Layanan</label>
                            <select name="service_type"
                                    id="service_type"
                                    class="modern-input @error('service_type') border-red-500 @enderror">
                                <option value="offline"
                                        {{ old('service_type') == 'offline' ? 'selected' : '' }}>Offline</option>
                                <option value="online"
                                        {{ old('service_type') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('service_type')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description"
                               class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description"
                                  id="description"
                                  rows="5"
                                  class="modern-input @error('description') border-red-500 @enderror"
                                  placeholder="Jelaskan secara rinci layanan yang Anda tawarkan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="subcategory_id"
                               class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                        <select name="subcategory_id"
                                id="subcategory_id"
                                class="modern-input @error('subcategory_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach ($subcategories as $sub)
                                <option value="{{ $sub->id }}"
                                        {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- SPESIFIKASI TAMBAHAN --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Spesifikasi Tambahan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="job_type"
                                   class="block mb-2 text-sm font-medium text-gray-700">Jenis Pekerjaan</label>
                            <select name="job_type"
                                    id="job_type"
                                    class="modern-input @error('job_type') border-red-500 @enderror">
                                <option value="">Pilih Jenis</option>
                                <option value="Full Time"
                                        {{ old('job_type') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time"
                                        {{ old('job_type') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Freelance"
                                        {{ old('job_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                        </div>
                        <div>
                            <label for="experience"
                                   class="block mb-2 text-sm font-medium text-gray-700">Pengalaman</label>
                            <select name="experience"
                                    id="experience"
                                    class="modern-input @error('experience') border-red-500 @enderror">
                                <option value="">Pilih Pengalaman</option>
                                <option value="0-1 Tahun"
                                        {{ old('experience') == '0-1 Tahun' ? 'selected' : '' }}>0-1 Tahun</option>
                                <option value="1-3 Tahun"
                                        {{ old('experience') == '1-3 Tahun' ? 'selected' : '' }}>1-3 Tahun</option>
                                <option value="3-5 Tahun"
                                        {{ old('experience') == '3-5 Tahun' ? 'selected' : '' }}>3-5 Tahun</option>
                                <option value=">5 Tahun"
                                        {{ old('experience') == '>5 Tahun' ? 'selected' : '' }}>&gt;5 Tahun</option>
                            </select>
                        </div>
                        <div>
                            <label for="industry"
                                   class="block mb-2 text-sm font-medium text-gray-700">Industri</label>
                            <select name="industry"
                                    id="industry"
                                    class="modern-input @error('industry') border-red-500 @enderror">
                                <option value="">Pilih Industri</option>
                                <option value="IT"
                                        {{ old('industry') == 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="Kesehatan"
                                        {{ old('industry') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Pendidikan"
                                        {{ old('industry') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Jasa"
                                        {{ old('industry') == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                <option value="Lainnya"
                                        {{ old('industry') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- LOKASI --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Lokasi</h2>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Pilih Lokasi (Klik di peta atau
                            Cari)</label>
                        <input type="hidden"
                               name="latitude"
                               id="latitude"
                               value="{{ old('latitude') }}">
                        <input type="hidden"
                               name="longitude"
                               id="longitude"
                               value="{{ old('longitude') }}">
                        <div id="map"
                             class="w-full h-80 rounded-lg border border-gray-300 mb-2"></div>
                    </div>
                    <div>
                        <label for="address"
                               class="block mb-2 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="address"
                                  id="address"
                                  rows="3"
                                  class="modern-input @error('address') border-red-500 @enderror"
                                  placeholder="Tuliskan alamat lengkap...">{{ old('address') }}</textarea>
                    </div>
                </div>

                {{-- KONTAK & GAMBAR --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Kontak & Media</h2>
                    <div>
                        <label for="contact"
                               class="block mb-2 text-sm font-medium text-gray-700">Kontak</label>
                        <input type="text"
                               name="contact"
                               id="contact"
                               value="{{ old('contact') }}"
                               class="modern-input @error('contact') border-red-500 @enderror"
                               placeholder="0812xxxxxxx atau email@example.com">
                    </div>

                    {{-- Upload Gambar --}}

                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                            class="bg-gray-900 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors duration-300 text-lg">
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            $('#subcategory_id').select2({
                placeholder: "Pilih Kategori",
                allowClear: true
            });

            // MAP
            const map = L.map('map').setView([-2.9761, 104.7754], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker;
            const oldLat = parseFloat("{{ old('latitude') }}");
            const oldLng = parseFloat("{{ old('longitude') }}");
            if (!isNaN(oldLat) && !isNaN(oldLng)) {
                marker = L.marker([oldLat, oldLng]).addTo(map);
                map.setView([oldLat, oldLng], 15);
            }

            map.on('click', e => {
                const {
                    lat,
                    lng
                } = e.latlng;
                if (marker) marker.setLatLng([lat, lng]);
                else marker = L.marker([lat, lng]).addTo(map);
                $('#latitude').val(lat);
                $('#longitude').val(lng);
            });

            L.Control.geocoder({
                defaultMarkGeocode: false,
                collapsed: true,
                placeholder: 'Cari lokasi...'
            }).on('markgeocode', e => {
                const c = e.geocode.center;
                if (marker) marker.setLatLng(c);
                else marker = L.marker(c).addTo(map);
                map.setView(c, 15);
                $('#latitude').val(c.lat);
                $('#longitude').val(c.lng);
            }).addTo(map);

            // IMAGE PREVIEW + REMOVE
            const imagesInput = document.getElementById('images');
            const previewContainer = document.getElementById('image-preview-container');
            let selectedFiles = [];

            imagesInput.addEventListener('change', e => {
                selectedFiles.push(...Array.from(e.target.files));
                updatePreview();
            });

            function updatePreview() {
                previewContainer.innerHTML = '';
                const dt = new DataTransfer();
                selectedFiles.forEach((file, i) => {
                    dt.items.add(file);
                    const reader = new FileReader();
                    reader.onload = ev => {
                        const box = document.createElement('div');
                        box.className = 'relative inline-block';
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'image-preview';
                        const del = document.createElement('button');
                        del.className =
                            'absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full p-1';
                        del.innerHTML =
                            `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
                        del.onclick = ev => {
                            ev.preventDefault();
                            selectedFiles.splice(i, 1);
                            updatePreview();
                        };
                        box.appendChild(img);
                        box.appendChild(del);
                        previewContainer.appendChild(box);
                    };
                    reader.readAsDataURL(file);
                });
                imagesInput.files = dt.files;
            }
        });
    </script>
</x-app-layout>
