<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Tambah Layanan</h1>

        <form action="{{ route('services.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <label class="block mb-2">Judul</label>
            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   class="border px-2 py-1 w-full mb-4">

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description"
                      class="border px-2 py-1 w-full mb-4">{{ old('description') }}</textarea>

            <label class="block mb-2">Harga</label>
            <input type="number"
                   name="price"
                   value="{{ old('price') }}"
                   class="border px-2 py-1 w-full mb-4">

            <label class="block mb-2">Kategori</label>
            <select name="subcategory_id"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Kategori</option>
                @foreach ($subcategories as $sub)
                    <option value="{{ $sub->id }}"
                            {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2">Lokasi (Klik di peta / Search)</label>
            <input type="hidden"
                   name="latitude"
                   id="latitude"
                   value="{{ old('latitude') }}">
            <input type="hidden"
                   name="longitude"
                   id="longitude"
                   value="{{ old('longitude') }}">
            <div id="map"
                 style="height: 300px;"
                 class="mb-4"></div>

            <label class="block mb-2">Jenis Pekerjaan</label>
            <select name="job_type"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Jenis Pekerjaan</option>
                <option value="Full Time"
                        {{ old('job_type') == 'Full Time' ? 'selected' : '' }}>
                    Full Time</option>
                <option value="Part Time"
                        {{ old('job_type') == 'Part Time' ? 'selected' : '' }}>
                    Part Time</option>
                <option value="Freelance"
                        {{ old('job_type') == 'Freelance' ? 'selected' : '' }}>
                    Freelance</option>
            </select>

            <label class="block mb-2">Pengalaman</label>
            <select name="experience"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Pengalaman</option>
                <option value="0-1 Tahun"
                        {{ old('experience') == '0-1 Tahun' ? 'selected' : '' }}>
                    0-1 Tahun</option>
                <option value="1-3 Tahun"
                        {{ old('experience') == '1-3 Tahun' ? 'selected' : '' }}>
                    1-3 Tahun</option>
                <option value="3-5 Tahun"
                        {{ old('experience') == '3-5 Tahun' ? 'selected' : '' }}>
                    3-5 Tahun</option>
                <option value=">5 Tahun"
                        {{ old('experience') == '>5 Tahun' ? 'selected' : '' }}>
                    >5 Tahun</option>
            </select>

            <label class="block mb-2">Industri</label>
            <select name="industry"
                    class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Industri</option>
                <option value="IT"
                        {{ old('industry') == 'IT' ? 'selected' : '' }}>
                    IT</option>
                <option value="Kesehatan"
                        {{ old('industry') == 'Kesehatan' ? 'selected' : '' }}>
                    Kesehatan</option>
                <option value="Pendidikan"
                        {{ old('industry') == 'Pendidikan' ? 'selected' : '' }}>
                    Pendidikan</option>
                <option value="Jasa"
                        {{ old('industry') == 'Jasa' ? 'selected' : '' }}>
                    Jasa</option>
                <option value="Lainnya"
                        {{ old('industry') == 'Lainnya' ? 'selected' : '' }}>
                    Lainnya</option>
            </select>

            <label class="block mb-2">Kontak</label>
            <input type="text"
                   name="contact"
                   value="{{ old('contact') }}"
                   class="border px-2 py-1 w-full mb-4">

            <label class="block mb-2">Alamat lengkap</label>
            <textarea name="address"
                      class="border px-2 py-1 w-full mb-4">{{ old('address') }}</textarea>


            <label class="block mb-2">Upload Gambar (boleh lebih dari 1)</label>
            <input type="file"
                   name="images[]"
                   multiple
                   class="mb-4">

            <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Leaflet Geocoder CSS & JS -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        var map = L.map('map').setView([-2.9761, 104.7754], 13); // Default Palembang

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var marker;

        // Jika ada old value, set marker
        var oldLat = parseFloat("{{ old('latitude') }}");
        var oldLng = parseFloat("{{ old('longitude') }}");
        if (oldLat && oldLng) {
            marker = L.marker([oldLat, oldLng]).addTo(map);
            map.setView([oldLat, oldLng], 13);
        }

        // Klik di map buat pasang marker
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

        // Tambahin search control setelah map dibuat
        L.Control.geocoder({
                defaultMarkGeocode: false,
                collapsed: false, // supaya search box terlihat
                placeholder: 'Cari lokasi...'
            })
            .on('markgeocode', function(e) {
                var center = e.geocode.center;
                if (marker) {
                    marker.setLatLng(center);
                } else {
                    marker = L.marker(center).addTo(map);
                }
                map.setView(center, 15);
                document.getElementById('latitude').value = center.lat;
                document.getElementById('longitude').value = center.lng;
            })
            .addTo(map);
    </script>
</x-app-layout>
