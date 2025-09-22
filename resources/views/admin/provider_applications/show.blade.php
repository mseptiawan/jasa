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
        <p><strong>Status:</strong> {{ $application->status }}</p>
        <p>
            <strong>Catatan Admin:</strong>
            {{ $application->admin_notes ?? '-' }}
        </p>

        <h2 class="text-xl font-semibold mt-4">File Upload</h2>
        <ul>
            @if($application->id_card)
            <li>
                <a href="{{ asset('storage/' . $application->id_card) }}"
                   target="_blank">KTP / Identitas</a>
            </li>
            @endif @if($application->selfie)
            <li>
                <a href="{{ asset('storage/' . $application->selfie) }}"
                   target="_blank">Selfie</a>
            </li>
            @endif @if($application->cv)
            <li>
                <a href="{{ asset('storage/' . $application->cv) }}"
                   target="_blank">CV</a>
            </li>
            @endif
        </ul>

        @if($application->status == 'pending')
        <form action="{{ route('admin.provider.applications.approve', $application->id) }}"
              method="POST"
              class="mt-4">

            <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 mr-2">
                Approve
            </button>
        </form>

        <form action="{{ route('admin.provider.applications.reject', $application->id) }}"
              method="POST">
            @csrf
            <input type="text"
                   name="admin_notes"
                   placeholder="Alasan reject"
                   class="border px-2 py-1 mb-2 w-full" />
            <button type="submit"
                    class="bg-red-500 text-white px-4 py-2">
                Reject
            </button>
        </form>
        @endif
    </div>
</x-app-layout>
