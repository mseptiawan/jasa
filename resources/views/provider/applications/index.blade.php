<x-app-layout>
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Pengajuan Saya</h1>

        @if($applications->isEmpty())
        <p>Belum ada pengajuan.</p>
        @else
        <table class="table-auto w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr>
                    <td class="border px-4 py-2">{{ $app->created_at->format('d M Y H:i') }}</td>
                    <td class="border px-4 py-2">{{ $app->user->full_name }}</td>

                    <td class="border px-4 py-2 capitalize">{{ $app->status }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('provider.applications.show', $app->slug) }}" class="text-blue-500">Lihat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</x-app-layout>