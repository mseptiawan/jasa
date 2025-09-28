<x-app-layout>
    {{-- Custom CSS & Montserrat Font --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        .text-primary {
            color: #2b3cd7;
        }

        /* Styling for cleaner inputs */
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

        /* Styling for consistent Select2 */
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

        /* Adjust spacing so content isn't hidden by fixed navbar */
        .content-container {
            margin-top: 5rem;
        }

        /* Styling for image previews */
        .image-preview-item {
            position: relative;
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }

        .delete-image-btn {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background-color: rgba(239, 68, 68, 0.8);
            border-radius: 9999px;
            padding: 0.25rem;
            transition: background-color 0.3s;
        }

        .delete-image-btn:hover {
            background-color: rgba(239, 68, 68, 1);
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
        {{-- Back to previous page --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-primary transition-colors flex items-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Main form container --}}
        <div class="bg-white p-8 rounded-2xl border border-gray-200 main-form-box">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-900">Edit Layanan</h1>

            <form action="{{ route('services.update', $service->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Main Details Section --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Detail Layanan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Judul Layanan</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $service->title) }}"
                                class="modern-input @error('title') border-red-500 @enderror">
                            @error('title')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-700">Harga (Rp)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}"
                                class="modern-input @error('price') border-red-500 @enderror" min="0">
                            @error('price')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="5"
                            class="modern-input @error('description') border-red-500 @enderror">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="subcategory_id" class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                        <select name="subcategory_id" id="subcategory_id"
                            class="modern-input @error('subcategory_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach ($subcategories as $sub)
                                <option value="{{ $sub->id }}"
                                    @if ($sub->id == old('subcategory_id', $service->subcategory_id)) selected @endif>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                {{-- Additional Specifications Section --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Spesifikasi Tambahan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="job_type" class="block mb-2 text-sm font-medium text-gray-700">Jenis Pekerjaan</label>
                            <select name="job_type" id="job_type"
                                class="modern-input @error('job_type') border-red-500 @enderror">
                                <option value="">Pilih Jenis</option>
                                <option value="Full Time" {{ old('job_type', $service->job_type) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time" {{ old('job_type', $service->job_type) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Freelance" {{ old('job_type', $service->job_type) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                            @error('job_type')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="experience" class="block mb-2 text-sm font-medium text-gray-700">Pengalaman</label>
                            <select name="experience" id="experience"
                                class="modern-input @error('experience') border-red-500 @enderror">
                                <option value="">Pilih Pengalaman</option>
                                <option value="0-1 Tahun" {{ old('experience', $service->experience) == '0-1 Tahun' ? 'selected' : '' }}>0-1 Tahun</option>
                                <option value="1-3 Tahun" {{ old('experience', $service->experience) == '1-3 Tahun' ? 'selected' : '' }}>1-3 Tahun</option>
                                <option value="3-5 Tahun" {{ old('experience', $service->experience) == '3-5 Tahun' ? 'selected' : '' }}>3-5 Tahun</option>
                                <option value=">5 Tahun" {{ old('experience', $service->experience) == '>5 Tahun' ? 'selected' : '' }}>&gt;5 Tahun</option>
                            </select>
                            @error('experience')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="industry" class="block mb-2 text-sm font-medium text-gray-700">Industri</label>
                            <select name="industry" id="industry"
                                class="modern-input @error('industry') border-red-500 @enderror">
                                <option value="">Pilih Industri</option>
                                @foreach (['IT', 'Kesehatan', 'Pendidikan', 'Jasa', 'Lainnya'] as $ind)
                                    <option value="{{ $ind }}"
                                        {{ old('industry', $service->industry) == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                                @endforeach
                            </select>
                            @error('industry')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Location Section --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Lokasi</h2>
                    <div class="form-group">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Pilih Lokasi (Klik di peta atau Cari)
                        </label>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $service->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $service->longitude) }}">
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
                            class="modern-input @error('address') border-red-500 @enderror">{{ old('address', $service->address) }}</textarea>
                        @error('address')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Contact & Media Section --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Kontak & Media</h2>
                    <div class="form-group">
                        <label for="contact" class="block mb-2 text-sm font-medium text-gray-700">Kontak</label>
                        <input type="text" name="contact" id="contact" value="{{ old('contact', $service->contact) }}"
                            class="modern-input @error('contact') border-red-500 @enderror"
                            placeholder="0812xxxxxxx atau email@example.com">
                        @error('contact')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Existing Images --}}
                    <div class="form-group">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Gambar yang Sudah Ada</label>
                        <div class="flex flex-wrap gap-2" id="existing-images-container">
                            {{-- Check if $service->images is a valid array before looping --}}
                            @if(is_array($service->images) && count($service->images) > 0)
                                @foreach($service->images as $path)
                                    <div class="image-preview-item" data-image-path="{{ $path }}">
                                        <img src="{{ asset('storage/' . $path) }}" alt="Service Image" class="image-preview">
                                        <button type="button" class="delete-image-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500">Tidak ada gambar yang diupload.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Upload New Images with Preview --}}
                    <div class="form-group">
                        <label for="images" class="block mb-2 text-sm font-medium text-gray-700">Upload Gambar Baru (opsional)</label>
                        <input type="file" name="images[]" id="images" multiple
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer @error('images') border-red-500 @enderror">
                        @error('images')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        {{-- Container for new image previews --}}
                        <div id="new-image-preview-container" class="mt-4 flex flex-wrap gap-2"></div>
                    </div>
                </div>

                {{-- Update Button --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="bg-gray-900 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors duration-300 text-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts for Leaflet Map & Select2 --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    {{-- jQuery and Select2 Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#subcategory_id, #job_type, #experience, #industry').select2({
                placeholder: "Pilih...",
                allowClear: true
            });

            // Leaflet Map Initialization
            var lat = parseFloat("{{ old('latitude', $service->latitude ?? -2.9761) }}");
            var lng = parseFloat("{{ old('longitude', $service->longitude ?? 104.7754) }}");

            var map = L.map('map').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            var marker;
            if (lat && lng) {
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 13);
            }

            map.on('click', function(e) {
                var latlng = e.latlng;
                if (marker) {
                    marker.setLatLng(latlng);
                } else {
                    marker = L.marker(latlng).addTo(map);
                }
                document.getElementById('latitude').value = latlng.lat;
                document.getElementById('longitude').value = latlng.lng;
            });

            L.Control.geocoder({
                defaultMarkGeocode: false,
                collapsed: true,
                placeholder: 'Cari lokasi...'
            }).on('markgeocode', function(e) {
                var center = e.geocode.center;
                if (marker) {
                    marker.setLatLng(center);
                } else {
                    marker = L.marker(center).addTo(map);
                }
                map.setView(center, 15);
                document.getElementById('latitude').value = center.lat;
                document.getElementById('longitude').value = center.lng;
            }).addTo(map);

            // --- Image Preview and Deletion Functionality ---
            const newImagesInput = document.getElementById('images');
            const newImagePreviewContainer = document.getElementById('new-image-preview-container');
            let selectedFiles = [];

            // Handler for new images
            newImagesInput.addEventListener('change', function(event) {
                selectedFiles.push(...Array.from(event.target.files));
                updateNewImagePreview();
            });

            function updateNewImagePreview() {
                newImagePreviewContainer.innerHTML = '';

                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                newImagesInput.files = dataTransfer.files;

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.classList.add('image-preview-item');
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('image-preview');
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.classList.add('delete-image-btn');
                        deleteBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>`;
                        deleteBtn.addEventListener('click', () => {
                            selectedFiles.splice(index, 1);
                            updateNewImagePreview();
                        });
                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(deleteBtn);
                        newImagePreviewContainer.appendChild(imgWrapper);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Handler for deleting existing images
            const existingImagesContainer = document.getElementById('existing-images-container');
            let deletedImagePaths = [];

            existingImagesContainer.addEventListener('click', function(e) {
                if (e.target.closest('.delete-image-btn')) {
                    const button = e.target.closest('.delete-image-btn');
                    const imageItem = button.closest('.image-preview-item');
                    const imagePath = imageItem.dataset.imagePath;

                    deletedImagePaths.push(imagePath);

                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_image_paths[]';
                    deleteInput.value = imagePath;
                    document.querySelector('form').appendChild(deleteInput);

                    imageItem.remove();
                }
            });
        });
    </script>
</x-app-layout>
