<x-app-layout>
    <div class="container mx-auto py-6 max-w-5xl">
        <h1 class="text-2xl font-bold mb-4">Highlight Service</h1>

        @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if($services->isEmpty())
        <p>Belum ada layanan untuk di-highlight.</p>
        @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Jasa</th>
                    <th class="border px-4 py-2">Status Highlight</th>
                    <th class="border px-4 py-2">Durasi</th>
                    <th class="border px-4 py-2">Fee</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td class="border px-4 py-2">{{ $service->title }}</td>
                    <td class="border px-4 py-2">
                        @if($service->is_highlight && $service->highlight_until > now())
                        Active
                        @else
                        Inactive
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        {{ $service->highlight_until ? $service->highlight_until->diffForHumans() : '-' }}
                    </td>
                    <td class="border px-4 py-2">
                        Rp {{ number_format($service->highlight_fee,0,',','.') }}
                    </td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('services.highlight.showPay', $service->slug) }}"
                           class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                            Bayar Highlight
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</x-app-layout>
