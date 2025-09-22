<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Detail Pengajuan</h1>

        <p><strong>Nama:</strong> {{ $application->user->full_name }}</p>
        <p><strong>Phone:</strong> {{ $application->phone_number }}</p>
        <p><strong>Alamat:</strong> {{ $application->address }}</p>
        <p><strong>Skills:</strong> {{ $application->skills }}</p>
        <p><strong>Experience:</strong> {{ $application->experience }}</p>
        <p><strong>Portfolio:</strong> {{ $application->portfolio }}</p>
        <p><strong>Education:</strong> {{ $application->education }}</p>
        <p><strong>Status:</strong>
            <span class="
                @if($application->status === 'approved') text-green-600
                @elseif($application->status === 'rejected') text-red-600
                @else text-yellow-600
                @endif
            ">
                {{ ucfirst($application->status) }}
            </span>
        </p>
        <p><strong>Catatan Admin:</strong> {{ $application->admin_notes ?? '-' }}</p>

        <h2 class="text-xl font-semibold mt-4">File Upload</h2>
        <ul class="list-disc ml-5">
            @if($application->id_card)
            <li><a href="{{ asset('storage/' . $application->id_card) }}" target="_blank" class="text-blue-600 underline">KTP / Identitas</a></li>
            @endif
            @if($application->selfie)
            <li><a href="{{ asset('storage/' . $application->selfie) }}" target="_blank" class="text-blue-600 underline">Selfie</a></li>
            @endif
            @if($application->cv)
            <li><a href="{{ asset('storage/' . $application->cv) }}" target="_blank" class="text-blue-600 underline">CV</a></li>
            @endif
        </ul>

        <div class="mt-6">
            <a href="{{ route('provider.applications') }}" class="text-blue-500 hover:underline">← Kembali ke daftar</a>
        </div>
    </div>
</x-app-layout>