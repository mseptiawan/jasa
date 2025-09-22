<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Edit Layanan</h1>

        <form action="{{ route('services.update', $service->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label class="block mb-2">Judul</label>
            <input type="text" name="title" value="{{ old('title', $service->title) }}" class="border px-2 py-1 w-full mb-4">

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description" class="border px-2 py-1 w-full mb-4">{{ old('description', $service->description) }}</textarea>

            <label class="block mb-2">Harga</label>
            <input type="number" name="price" value="{{ old('price', $service->price) }}" class="border px-2 py-1 w-full mb-4">

            <label class="block mb-2">Kategori</label>
            <select name="subcategory_id" class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Kategori</option>
                @foreach($subcategories as $sub)
                <option value="{{ $sub->id }}" @if($sub->id == $service->subcategory_id) selected @endif>{{ $sub->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2">Jenis Pekerjaan</label>
            <select name="job_type" class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Jenis Pekerjaan</option>
                <option value="Full Time" {{ old('job_type', $service->job_type) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                <option value="Part Time" {{ old('job_type', $service->job_type) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                <option value="Freelance" {{ old('job_type', $service->job_type) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
            </select>

            <label class="block mb-2">Pengalaman</label>
            <select name="experience" class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Pengalaman</option>
                <option value="0-1 Tahun" {{ old('experience', $service->experience) == '0-1 Tahun' ? 'selected' : '' }}>0-1 Tahun</option>
                <option value="1-3 Tahun" {{ old('experience', $service->experience) == '1-3 Tahun' ? 'selected' : '' }}>1-3 Tahun</option>
                <option value="3-5 Tahun" {{ old('experience', $service->experience) == '3-5 Tahun' ? 'selected' : '' }}>3-5 Tahun</option>
                <option value=">5 Tahun" {{ old('experience', $service->experience) == '>5 Tahun' ? 'selected' : '' }}>>5 Tahun</option>
            </select>

            <label class="block mb-2">Industri</label>
            <select name="industry" class="border px-2 py-1 w-full mb-4">
                <option value="">Pilih Industri</option>
                <option value="IT" {{ old('industry', $service->industry) == 'IT' ? 'selected' : '' }}>IT</option>
                <option value="Kesehatan" {{ old('industry', $service->industry) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                <option value="Pendidikan" {{ old('industry', $service->industry) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                <option value="Jasa" {{ old('industry', $service->industry) == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                <option value="Lainnya" {{ old('industry', $service->industry) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <label class="block mb-2">Kontak</label>
            <input type="text" name="contact" value="{{ old('contact', $service->contact) }}" class="border px-2 py-1 w-full mb-4">

            <label class="block mb-2">Alamat lengkap</label>
            <textarea name="address" class="border px-2 py-1 w-full mb-4">{{ old('address', $service->address) }}</textarea>


            <label class="block mb-2">Upload Gambar Baru</label>
            <input type="file" name="images[]" multiple class="mb-4">

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>