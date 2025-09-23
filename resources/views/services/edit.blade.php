<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Edit Layanan</h1>

        <form action="{{ route('services.update', $service->slug) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label class="block mb-2">Judul</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $service->title) }}"
                   class="border px-2 py-1 w-full mb-4">
            @error('title')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description"
                      class="border px-2 py-1 w-full mb-4">{{ old('description', $service->description) }}</textarea>
            @error('description')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Harga</label>
            <input type="number"
                   name="price"
                   value="{{ old('price', $service->price) }}"
                   class="border px-2 py-1 w-full mb-4">
            @error('price')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Kategori</label>
            <select name="subcategory_id"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Kategori</option>
                @foreach ($subcategories as $sub)
                    <option value="{{ $sub->id }}"
                            @if ($sub->id == old('subcategory_id', $service->subcategory_id)) selected @endif>{{ $sub->name }}</option>
                @endforeach
            </select>
            @error('subcategory_id')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Jenis Pekerjaan</label>
            <select name="job_type"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Jenis Pekerjaan</option>
                <option value="Full Time"
                        {{ old('job_type', $service->job_type) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                <option value="Part Time"
                        {{ old('job_type', $service->job_type) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                <option value="Freelance"
                        {{ old('job_type', $service->job_type) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
            </select>

            <label class="block mb-2">Pengalaman</label>
            <select name="experience"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Pengalaman</option>
                <option value="0-1 Tahun"
                        {{ old('experience', $service->experience) == '0-1 Tahun' ? 'selected' : '' }}>0-1 Tahun
                </option>
                <option value="1-3 Tahun"
                        {{ old('experience', $service->experience) == '1-3 Tahun' ? 'selected' : '' }}>1-3 Tahun
                </option>
                <option value="3-5 Tahun"
                        {{ old('experience', $service->experience) == '3-5 Tahun' ? 'selected' : '' }}>3-5 Tahun
                </option>
                <option value=">5 Tahun"
                        {{ old('experience', $service->experience) == '>5 Tahun' ? 'selected' : '' }}>>5 Tahun</option>
            </select>
            @error('experience')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Industri</label>
            <select name="industry"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Industri</option>
                @foreach (['IT', 'Kesehatan', 'Pendidikan', 'Jasa', 'Lainnya'] as $ind)
                    <option value="{{ $ind }}"
                            {{ old('industry', $service->industry) == $ind ? 'selected' : '' }}>{{ $ind }}
                    </option>
                @endforeach
            </select>
            @error('industry')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Kontak</label>
            <input type="text"
                   name="contact"
                   value="{{ old('contact', $service->contact) }}"
                   class="border px-2 py-1 w-full mb-4">
            @error('contact')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Alamat lengkap</label>
            <textarea name="address"
                      class="border px-2 py-1 w-full mb-4">{{ old('address', $service->address) }}</textarea>
            @error('address')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <label class="block mb-2">Upload Gambar Baru</label>
            <input type="file"
                   name="images[]"
                   multiple
                   class="mb-4">

            <label class="block mb-2">Lokasi (Klik di peta / Search)</label>
            <input type="hidden"
                   name="latitude"
                   id="latitude"
                   value="{{ old('latitude', $service->latitude) }}">
            @error('latitude')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <input type="hidden"
                   name="longitude"
                   id="longitude"
                   value="{{ old('longitude', $service->longitude) }}">
            @error('longitude')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror

            <div id="map"
                 style="height: 300px;"
                 class="mb-4"></div>

            <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>

    <link rel="stylesheet"
          href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
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
            collapsed: false,
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
    </script>
</x-app-layout>
